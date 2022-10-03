DROP FUNCTION sla_orak;

DROP FUNCTION munkaoraban;
DROP FUNCTION uno_bekuldes;
DROP FUNCTION uno_reakcio;
DROP FUNCTION uno_atadas;
DROP FUNCTION uno_sla;
DROP FUNCTION uno_sla_meres;

--munkaoraban: a ket idopont kozott mennyi munkaora volt
CREATE OR REPLACE 
FUNCTION munkaoraban(p_begin IN TIMESTAMP, p_end IN TIMESTAMP) RETURNS double precision AS $$
  WITH
    holkinel AS (
      SELECT tstzrange($1, $2, '[)') AS r
    ),
    munkaido AS (
      SELECT tstzrange(day + interval '8 hours', day + interval '18 hours') AS r --8:00-18:00
        FROM (SELECT date_trunc('day', $1) + make_interval(days=>n) AS day 
                FROM generate_series(0, 
                                     GREATEST(2, CEIL(1 + extract('days' FROM ($2 - $1)))::int)) AS n) d 
        WHERE extract(dow from d.day) NOT IN (0, 6)
    )
  SELECT extract(hours FROM SUM(UPPER(holkinel.r * munkaido.r) - LOWER(holkinel.r * munkaido.r))) AS orak 
    FROM holkinel JOIN munkaido ON holkinel.r && munkaido.r
$$ LANGUAGE sql LEAKPROOF;

--bekuldes: bekuldes ideje
CREATE OR REPLACE 
FUNCTION uno_bekuldes(p_bug_id IN INTEGER) RETURNS TIMESTAMP AS $$
  SELECT to_timestamp(date_submitted) AT TIME ZONE 'UTC' 
    FROM mantis_bug_table 
    WHERE id = $1
$$ LANGUAGE sql LEAKPROOF;

--reakcio: elso reakcio ideje
CREATE OR REPLACE 
FUNCTION uno_reakcio(p_bug_id IN INTEGER) RETURNS TIMESTAMP AS $$
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
               ))) AT TIME ZONE 'UTC'
$$ LANGUAGE sql LEAKPROOF;

--atadva: atadas ideje
CREATE OR REPLACE 
FUNCTION uno_atadas(p_bug_id IN INTEGER) RETURNS TIMESTAMP AS $$
  SELECT to_timestamp(MIN(A.date_modified)) AT TIME ZONE 'UTC'
    FROM mantis_bug_history_table A
    WHERE A.new_value::int >= 80 AND
          A.field_name = 'status' AND 
          A.bug_id = $1
$$ LANGUAGE sql LEAKPROOF;

CREATE OR REPLACE
FUNCTION uno_sla(priority IN smallint, p_tipus IN smallint) RETURNS int AS $$
BEGIN
  RETURN(CASE p_tipus 
    WHEN 0 THEN CASE priority WHEN 30 THEN 4 WHEN 40 THEN 8 ELSE 10 END
    WHEN 2 THEN CASE priority WHEN 30 THEN 50 WHEN 40 THEN 50 ELSE 100 END 
    ELSE NULL
  END);
END;
$$ LANGUAGE plpgsql LEAKPROOF;

