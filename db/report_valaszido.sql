SELECT A.id AS "mantis", A.summary AS "leírás",
       CASE priority WHEN 30 THEN 'Normál' WHEN 40 THEN 'Közepesen magas' when 50 then 'Sürgős' ELSE 'Kritikus' END AS "prioritás",
       A.date_submitted AS "bejelentés",
       A.reakcio AS "válasz",
       munkaoraban(A.date_submitted, A.reakcio) AS "reakcióidő",
       A.sla AS "SLA",
       (A.sla > munkaoraban(A.date_submitted, A.reakcio)) AS "megfelelő"
  FROM (SELECT A.id, A.summary, A.priority, 
               to_timestamp(A.date_submitted) AS date_submitted,
               uno_reakcio(A.id) AS reakcio,
               uno_sla(A.priority::smallint, 0::smallint) AS sla
          FROM mantis_bug_table A
          WHERE date_submitted > 1656626400 --2022.07.01
  ) A;

--vim:se et shiftwidth=2 fenc=utf-8:
