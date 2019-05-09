CREATE OR REPLACE FUNCTION workhours_between(timestamp with time zone, timestamp with time zone, smallint default 9, smallint default 17) RETURNS bigint AS $$
SELECT COUNT(0)
  FROM generate_series($1, $2, '1 hour') AS S(a)
  WHERE extract('dow' from S.a) NOT IN (0, 6) AND extract('hour' FROM S.a) BETWEEN $3 AND $4;
$$ LANGUAGE SQL;

SELECT A.id, A.summary, A.tipus, A.bekuldve, A.elso_reakcio, A.lezarva, A.hatarido,
       workhours_between(A.bekuldve, A.elso_reakcio) AS reakcioido_ora,
       workhours_between(A.bekuldve, A.lezarva) AS brutto_varakozas_ora,
       LEAST(workhours_between(A.bekuldve, A.lezarva)*0.9, A.biztositora_varakozas) AS biztositora_varakozas_ora,
       workhours_between(A.bekuldve, A.lezarva) - COALESCE(LEAST(workhours_between(A.bekuldve, A.lezarva)*0.9, A.biztositora_varakozas), 0) AS netto_megoldasi_ido_ora
  FROM (
SELECT A.id, A.summary,
       (SELECT CASE MIN(H.new_value) WHEN '991' THEN 'H' ELSE 'T' END
          FROM mantis_bug_history_table H
          WHERE NOT EXISTS (SELECT 1 FROM mantis_bug_history_table X
                              WHERE X.id > H.id AND
                                    X.type = 0 AND X.field_name = 'status' AND X.new_value IN ('991', '993') AND X.bug_id = H.bug_id) AND
                H.type = 0 AND H.field_name = 'status' AND H.new_value IN ('991', '993') AND H.bug_id = A.id) AS tipus,
       TO_TIMESTAMP(A.date_submitted) AS bekuldve,
       (SELECT TO_TIMESTAMP(CASE Y.value WHEN '' THEN NULL ELSE cast(Y.value AS bigint) END)
          FROM mantis_custom_field_string_table Y, mantis_custom_field_table X
          WHERE Y.bug_id = A.id AND Y.field_id = X.id AND X.name = 'határidő') AS hatarido,
       (SELECT TO_TIMESTAMP(MIN(H.date_submitted)) FROM mantis_user_table U, mantis_bugnote_table H WHERE U.access_level > 25 AND U.id = H.reporter_id AND H.bug_id = A.id) AS elso_reakcio,
       (SELECT TO_TIMESTAMP(MIN(H.date_modified)) FROM mantis_bug_history_table H WHERE H.type = 0 AND H.field_name = 'status' AND H.new_value IN ('991', '993') AND H.bug_id = A.id) AS lezarva,
       (SELECT SUM(CASE WHEN B.w = 'B' AND B.prev_w = 'U' THEN workhours_between(TO_TIMESTAMP(B.prev_submitted), TO_TIMESTAMP(B.date_submitted)) ELSE 0 END)
         FROM (WITH sor AS
(SELECT A.id bug_id, A.date_submitted, 'B' w
  FROM mantis_bug_table A
  WHERE A.id IN (SELECT H.bug_id FROM mantis_bug_history_table H WHERE H.type = 0 AND H.field_name = 'status' AND H.new_value IN ('991', '993'))
UNION ALL
SELECT A.bug_id, A.date_submitted, (CASE WHEN B.access_level > 25 THEN 'U' ELSE 'B' END) w
  FROM mantis_user_table B, mantis_bugnote_table A
  WHERE B.id = A.reporter_id AND A.view_state < 50 AND
        A.bug_id IN (SELECT H.bug_id FROM mantis_bug_history_table H WHERE H.type = 0 AND H.field_name = 'status' AND H.new_value IN ('991', '993'))
UNION ALL
SELECT A.bug_id, MIN(A.date_modified), 'U' w
  FROM mantis_bug_history_table A
  WHERE NOT EXISTS (SELECT 1 FROM mantis_bug_history_table X
                      WHERE X.id > A.id AND X.bug_id = A.bug_id AND X.new_value IN ('991', '993') AND X.type = 0 AND X.field_name = 'status') AND
        EXISTS (SELECT 1 FROM mantis_user_table Y, mantis_bugnote_table X WHERE Y.access_level <= 25 AND Y.id = X.reporter_id AND X.bug_id = A.bug_id) AND
        A.new_value IN ('991', '993') AND A.type = 0 AND A.field_name = 'status'
  GROUP BY A.bug_id
UNION ALL
SELECT A.bug_id, MIN(A.date_modified), 'B' w
  FROM mantis_bug_history_table A
  WHERE NOT EXISTS (SELECT 1 FROM mantis_bug_history_table X
                      WHERE X.id > A.id AND X.bug_id = A.bug_id AND X.new_value IN ('991', '993') AND X.type = 0 AND X.field_name = 'status') AND
        NOT EXISTS (SELECT 1 FROM mantis_user_table Y, mantis_bugnote_table X WHERE Y.access_level <= 25 AND Y.id = X.reporter_id AND X.bug_id = A.bug_id) AND
        A.new_value IN ('991', '993') AND A.type = 0 AND A.field_name = 'status'
  GROUP BY A.bug_id
)
               SELECT B.*,
                      LAG(B.date_submitted, 1, B.date_submitted) OVER (PARTITION BY B.bug_id ORDER BY B.date_submitted) prev_submitted,
                      LAG(B.w, 1, B.w) OVER (PARTITION BY B.bug_id ORDER BY B.date_submitted) prev_w
          FROM sor B
          WHERE B.bug_id = A.id
            ) B
    ) AS biztositora_varakozas
  FROM mantis_bug_table A
  WHERE EXISTS (SELECT 1 FROM mantis_bug_history_table H WHERE H.type = 0 AND H.field_name = 'status' AND H.new_value IN ('991', '993') AND H.bug_id = A.id)
  ORDER BY A.id DESC
  ) A;
