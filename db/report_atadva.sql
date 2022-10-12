SELECT A.id AS "mantis", A.summary AS "le�r�s",
       CASE priority WHEN 30 THEN 'Norm�l' WHEN 40 THEN 'K�zepesen magas' when 50 then 'S�rg�s' ELSE 'Kritikus' END AS "priorit�s",
       A.date_submitted AS "bejelent�s",
       fejlesztonel_munkaora(A.id, A.date_submitted, COALESCE(A.atadas, localtimestamp)) AS "�tad�s",
       A.sla AS "SLA",
       CASE WHEN A.sla > fejlesztonel_munkaora(A.id, A.date_submitted, COALESCE(A.atadas, localtimestamp)) THEN 'Rendben' ELSE 'Probl�ma' END AS "ki�rt�kel�s"
  FROM (SELECT A.id, A.priority, A.summary,
               to_timestamp(A.date_submitted) AS date_submitted,
               uno_atadas(A.id)  AS atadas,
               uno_sla(A.priority::smallint, 2::smallint) AS sla
          FROM mantis_bug_table A
          WHERE date_submitted > 1656626400 --2022.07.01
  ) A
  ORDER BY 1;
--vim:se et shiftwidth=2 fenc=utf-8:
