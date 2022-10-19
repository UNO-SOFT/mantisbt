--munkaorak: munkaorak tstzrange
DROP MATERIALIZED VIEW mw_bug;
DROP FUNCTION fejlesztonel_munkaora;
DROP FUNCTION fejlesztonel;
DROP FUNCTION mikorkinel;
DROP FUNCTION munkaoraban;
DROP FUNCTION munkaorak;
DROP FUNCTION uno_bekuldes;
DROP FUNCTION uno_reakcio;
DROP FUNCTION uno_atadas;
DROP FUNCTION uno_sla;

CREATE OR REPLACE
FUNCTION munkaorak(p_begin IN TIMESTAMP WITH TIME ZONE, p_end IN TIMESTAMP WITH TIME ZONE)
RETURNS setof tstzrange AS $$
  SELECT tstzrange(day + interval '8 hours', day + interval '18 hours') AS r --8:00-18:00
    FROM (SELECT date_trunc('day', COALESCE(LEAST($1, $2), localtimestamp)) + make_interval(days=>n) AS day
            FROM generate_series(0,
                                 GREATEST(2, CEIL(1 + extract('days' FROM (COALESCE(GREATEST($1, $2), localtimestamp) - COALESCE(LEAST($1, $2), localtimestamp))))::int)) AS n) d
    WHERE extract(dow from d.day) NOT IN (0, 6)
$$ LANGUAGE sql LEAKPROOF;

--munkaoraban: a ket idopont kozott mennyi munkaora volt
CREATE OR REPLACE
FUNCTION munkaoraban(p_begin IN TIMESTAMP WITH TIME ZONE, p_end IN TIMESTAMP WITH TIME ZONE) RETURNS double precision AS $$
  SELECT COALESCE(extract(hours FROM SUM(UPPER(A.r * B.r) - LOWER(A.r * B.r))), 0) AS orak
    FROM (SELECT tstzrange AS r FROM tstzrange(COALESCE(LEAST($1, $2), localtimestamp), COALESCE(GREATEST($1, $2), localtimestamp), '[)')) A JOIN (SELECT munkaorak AS r FROM munkaorak($1, $2)) B ON A.r && B.r
$$ LANGUAGE sql LEAKPROOF;

--mikorkinel: mikor kinel volt F a fejleszto, B a biztosito
CREATE OR REPLACE
FUNCTION mikorkinel(p_bug_id IN INTEGER, p_begin IN TIMESTAMP WITH TIME ZONE, p_end IN TIMESTAMP WITH TIME ZONE,
                    mikor OUT TIMESTAMP WITH TIME ZONE, kinel OUT CHAR) RETURNS setof record AS $$
  SELECT COALESCE(p_begin, to_timestamp(date_submitted)) AS mikor, 'F' AS kinel FROM mantis_bug_table
    WHERE ($2 IS NULL OR $2 <= to_timestamp(date_submitted)) AND
          ($3 IS NULL OR to_timestamp(date_submitted) < $3) AND
          id = $1
  UNION
  SELECT to_timestamp(date_modified) AS mikor, CASE WHEN new_value::int IN (20, 27, 30, 80, 90) THEN 'B' ELSE 'F' END AS kinel
    FROM mantis_bug_history_table A
    WHERE ($2 IS NULL OR $2 <= to_timestamp(date_modified)) AND
          ($3 IS NULL OR to_timestamp(date_modified) < $3) AND
          field_name = 'status' AND bug_id = $1
$$ LANGUAGE sql LEAKPROOF;

--fejlesztonel: mikor volt a fejlesztonel
CREATE OR REPLACE
FUNCTION fejlesztonel(p_bug_id IN INTEGER, p_begin IN TIMESTAMP WITH TIME ZONE, p_end IN TIMESTAMP WITH TIME ZONE) RETURNS tstzrange AS $$
  SELECT tstzrange(A.mikor,
                   LEAD(A.mikor, 1, A.mikor + interval '1 second') OVER (ORDER BY A.mikor),
                   '[)') AS r
    FROM mikorkinel($1, $2, $3) A
    WHERE A.kinel = 'F'
$$ LANGUAGE sql LEAKPROOF;

--fejlesztonel_munkaora: mennyit volt a fejlesztonel
CREATE OR REPLACE
FUNCTION fejlesztonel_munkaora(p_bug_id IN INTEGER, p_begin IN TIMESTAMP WITH TIME ZONE, p_end IN TIMESTAMP WITH TIME ZONE) RETURNS double precision AS $$
  SELECT COALESCE(extract(hours FROM SUM(UPPER(A.r * B.r) - LOWER(A.r * B.r))), 0) AS orak
    FROM (SELECT fejlesztonel AS r FROM fejlesztonel($1, $2, $3)) A
    JOIN (SELECT munkaorak AS r FROM munkaorak($2, $3)) B ON A.r && B.r
$$ LANGUAGE sql LEAKPROOF;

--bekuldes: bekuldes ideje
CREATE OR REPLACE
FUNCTION uno_bekuldes(p_bug_id IN INTEGER) RETURNS TIMESTAMP WITH TIME ZONE AS $$
  SELECT to_timestamp(date_submitted)
    FROM mantis_bug_table
    WHERE id = $1
$$ LANGUAGE sql LEAKPROOF;

--reakcio: elso reakcio ideje
CREATE OR REPLACE
FUNCTION uno_reakcio(p_bug_id IN INTEGER) RETURNS TIMESTAMP WITH TIME ZONE AS $$
  SELECT to_timestamp(LEAST((--elso statusz valtas
                SELECT MIN(date_modified)
                  FROM mantis_user_table B, mantis_bug_history_table A
                  WHERE B.access_level > 25 AND B.id = A.user_id AND A.field_name = 'status' AND
                        A.bug_id = $1
               ),
               (--vagy az elso lathato bejegyzes
                SELECT MIN(date_submitted)
                  FROM mantis_user_table B, mantis_bugnote_table A
                  WHERE B.access_level > 25 AND B.id = A.reporter_id AND A.view_state < 50 AND
                        A.bug_id = $1
               )))
$$ LANGUAGE sql LEAKPROOF;

--atadva: atadas ideje
CREATE OR REPLACE
FUNCTION uno_atadas(p_bug_id IN INTEGER) RETURNS TIMESTAMP WITH TIME ZONE AS $$
  SELECT to_timestamp(MIN(A.date_modified))
    FROM mantis_bug_history_table A
    WHERE A.new_value::int >= 80 AND
          A.field_name = 'status' AND
          A.bug_id = $1
$$ LANGUAGE sql LEAKPROOF;

CREATE OR REPLACE
FUNCTION uno_sla(priority IN smallint, p_tipus IN smallint) RETURNS int AS $$
BEGIN
  RETURN(CASE p_tipus
    --g_priority_enum_string  = '30:normal,40:high,50:urgent,60:immediate';
    WHEN 0 THEN CASE priority WHEN 60 THEN  4 when 50 then 8  WHEN 40 THEN  10 ELSE  50 END
    WHEN 1 THEN CASE priority WHEN 60 THEN 12 when 50 then 12 WHEN 40 THEN  50 ELSE 200 END
    WHEN 2 THEN CASE priority WHEN 60 THEN 50 when 50 then 50 WHEN 40 THEN 100 ELSE 200 END
    ELSE NULL
  END);
END;
$$ LANGUAGE plpgsql LEAKPROOF;

CREATE MATERIALIZED VIEW mw_bug AS 
  SELECT A.*, 
         munkaoraban(A.bekuldes, COALESCE(A.reakcio, localtimestamp)) AS reakcio_munkaora,
         fejlesztonel_munkaora(A.id, A.bekuldes, COALESCE(GREATEST(A.atadas, A.due_date), localtimestamp)) AS fejlesztonel_munkaora
    FROM (SELECT X.id, 
                 to_timestamp(X.date_submitted) AS bekuldes,
                 uno_atadas(X.id) AS atadas,
                 uno_reakcio(X.id) AS reakcio,
                 to_timestamp(X.due_date) AS due_date
    FROM mantis_bug_table X) A;

CREATE UNIQUE INDEX U_mw_bug ON mw_bug(id);

