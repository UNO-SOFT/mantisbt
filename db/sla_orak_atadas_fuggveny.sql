DROP FUNCTION munkaorak;
CREATE OR REPLACE
FUNCTION munkaorak(p_begin IN TIMESTAMP WITH TIME ZONE, p_end IN TIMESTAMP WITH TIME ZONE) 
RETURNS setof tstzrange AS $$
  SELECT tstzrange(day + interval '8 hours', day + interval '18 hours') AS r --8:00-18:00
    FROM (SELECT date_trunc('day', LEAST($1, $2)) + make_interval(days=>n) AS day 
            FROM generate_series(0, 
                                 GREATEST(2, CEIL(1 + extract('days' FROM (GREATEST($1, $2) - LEAST($1, $2))))::int)) AS n) d 
    WHERE extract(dow from d.day) NOT IN (0, 6)
$$ LANGUAGE sql LEAKPROOF;

--munkaoraban: a ket idopont kozott mennyi munkaora volt
DROP FUNCTION munkaoraban;
CREATE OR REPLACE 
FUNCTION munkaoraban(p_begin IN TIMESTAMP WITH TIME ZONE, p_end IN TIMESTAMP WITH TIME ZONE) RETURNS double precision AS $$
  SELECT COALESCE(extract(hours FROM SUM(UPPER(A.r * B.r) - LOWER(A.r * B.r))), 0) AS orak 
    FROM (SELECT tstzrange AS r FROM tstzrange($1, $2, '[)')) A JOIN (SELECT munkaorak AS r FROM munkaorak($1, $2)) B ON A.r && B.r
$$ LANGUAGE sql LEAKPROOF;

DROP FUNCTION mikorkinel;
CREATE OR REPLACE
FUNCTION mikorkinel(p_bug_id IN INTEGER, p_begin IN TIMESTAMP WITH TIME ZONE, p_end IN TIMESTAMP WITH TIME ZONE,
                    mikor OUT TIMESTAMP WITH TIME ZONE, kinel OUT CHAR) RETURNS setof record AS $$
  SELECT to_timestamp(date_submitted) AS mikor, 'U' AS kinel from mantis_bug_table WHERE id = $1
  UNION 
  SELECT to_timestamp(date_modified) AS mikor, CASE WHEN new_value::int IN (20, 27, 30, 80, 90) THEN 'B' ELSE 'U' END AS kinel 
    FROM mantis_bug_history_table A
    WHERE bug_id = $1 AND field_name ='status'
$$ LANGUAGE sql LEAKPROOF;

CREATE OR REPLACE
FUNCTION biztositonal_orak(p_bug_id IN INTEGER, p_begin IN TIMESTAMP, p_end IN TIMESTAMP) RETURNS double precision AS $$
  SELECT
SELECT date_submitted, 'U' AS kinel from mantis_bug_table WHERE id = $1
UNION 
SELECT date_modified, CASE WHEN new_value::int IN (20, 27, 30, 80, 90) THEN 'B' ELSE 'U' END AS kinel 
 FROM mantis_bug_history_table A
 WHERE NOT EXISTS (SELECT 1 FROM mantis_bug_history_table X 
                                    WHERE X.date_modified <= A.date_modified AND X.new_value::int = 80 AND X.field_name = 'status' AND X.bug_id = A.bug_id) AND 
                      bug_id = 13749 AND field_name ='status') A)
$$ LANGUAGE sql LEAKPROOF;

--bekuldes: bekuldes ideje
DROP FUNCTION uno_bekuldes;
CREATE OR REPLACE 
FUNCTION uno_bekuldes(p_bug_id IN INTEGER) RETURNS TIMESTAMP AS $$
  SELECT to_timestamp(date_submitted) AT TIME ZONE 'UTC' 
    FROM mantis_bug_table 
    WHERE id = $1
$$ LANGUAGE sql LEAKPROOF;

--reakcio: elso reakcio ideje
DROP FUNCTION uno_reakcio;
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
DROP FUNCTION uno_atadas;
CREATE OR REPLACE 
FUNCTION uno_atadas(p_bug_id IN INTEGER) RETURNS TIMESTAMP AS $$
  SELECT to_timestamp(MIN(A.date_modified)) AT TIME ZONE 'UTC'
    FROM mantis_bug_history_table A
    WHERE A.new_value::int >= 80 AND
          A.field_name = 'status' AND 
          A.bug_id = $1
$$ LANGUAGE sql LEAKPROOF;

DROP FUNCTION uno_sla;
CREATE OR REPLACE
FUNCTION uno_sla(priority IN smallint, p_tipus IN smallint) RETURNS int AS $$
BEGIN
  RETURN(CASE p_tipus 
    --g_priority_enum_string	= '30:normal,40:high,60:immediate';
    WHEN 0 THEN CASE priority WHEN 60 THEN  4 WHEN 40 THEN  10 ELSE  50 END
    WHEN 1 THEN CASE priority WHEN 60 THEN 12 WHEN 40 THEN  50 ELSE 200 END
    WHEN 2 THEN CASE priority WHEN 60 THEN 50 WHEN 40 THEN 200 ELSE 400 END 
    ELSE NULL
  END);
END;
$$ LANGUAGE plpgsql LEAKPROOF;

