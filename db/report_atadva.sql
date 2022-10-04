SELECT A.id AS "mantisjegy_szám", 
       A.summary, 
       CASE priority WHEN 30 THEN  'Normál' WHEN 40 THEN 'Sürgõs' ELSE 'Kritikus' END AS "prioritás", 
	   A.date_submitted AS "bejelentés", 
       munkaoraban(A.date_submitted, A.atadas) AS "átadás",
       A.sla AS "SLA",
       (A.sla >ó munkaoraban(A.date_submitted, A.atadas)) AS "megfelelés"
  FROM (SELECT A.id, A.priority, A.summary, 
               to_timestamp(A.date_submitted) AT TIME ZONE 'UTC' AS date_submitted,
               uno_atadas(A.id) AS atadas,
               uno_sla(A.priority::smallint, 2::smallint) AS sla
          FROM mantis_bug_table A
          WHERE date_submitted > 1656626400 --2022.07.01
  ) A;
--vim:se et shiftwidth=2 fenc=utf-8:
