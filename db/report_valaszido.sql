SELECT A.id AS "Mantisjegy_száma",
       CASE A.priority WHEN 30 THEN 'Normál' WHEN 40 THEN 'Sürgős' ELSE 'Kritikus' END AS "prioritás", 
       A.date_submitted AS "bejelentés", 
       A.reakcio AS "válasz",
       munkaoraban(A.date_submitted, COALESCE(A.reakcio, localtimestamp)) AS "reakcióidő",
       A.sla, 
       (A.sla > munkaoraban(A.date_submitted, A.reakcio)) AS "kiértékelés"
  FROM (SELECT A.id, A.priority, A.summary, 
               to_timestamp(A.date_submitted) AS date_submitted,
               uno_reakcio(A.id) AS reakcio,
               uno_sla(A.priority::smallint, 0::smallint) AS sla
          FROM mantis_bug_table A
          WHERE date_submitted > 1656626400 --2022.07.01
  ) A;
--vim:se et shiftwidth=2 fenc=utf-8:
