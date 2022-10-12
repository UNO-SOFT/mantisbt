SELECT A.id AS "mantis", A.summary AS "leírás",
       CASE priority WHEN 30 THEN 'Normál' WHEN 40 THEN 'Közepesen magas' when 50 then 'Sürgõs' ELSE 'Kritikus' END AS "prioritás",
       A.date_submitted AS "bejelentés",
       fejlesztonel_munkaora(A.id, A.date_submitted, COALESCE(A.atadas, localtimestamp)) AS "átadás",
       A.sla AS "SLA",
       CASE WHEN A.sla > fejlesztonel_munkaora(A.id, A.date_submitted, COALESCE(A.atadas, localtimestamp)) THEN 'Rendben' ELSE 'Probléma' END AS "kiértékelés"
  FROM (SELECT A.id, A.priority, A.summary,
               to_timestamp(A.date_submitted) AS date_submitted,
               uno_atadas(A.id)  AS atadas,
               uno_sla(A.priority::smallint, 2::smallint) AS sla
          FROM mantis_bug_table A
          WHERE date_submitted > 1656626400 --2022.07.01
  ) A
  ORDER BY 1;
--vim:se et shiftwidth=2 fenc=utf-8:
