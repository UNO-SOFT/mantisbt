SELECT A.id AS "mantis", A.summary AS "le�r�s",
       CASE priority WHEN 30 THEN 'Norm�l' WHEN 40 THEN 'K�zepesen magas' when 50 then 'S�rg�s' ELSE 'Kritikus' END AS "priorit�s",
       A.date_submitted AS "bejelent�s",
       A.reakcio AS "v�lasz",
       munkaoraban(A.date_submitted, A.reakcio) AS "reakci�id�",
       A.sla AS "SLA",
    CASE WHEN A.sla > munkaoraban(A.date_submitted, A.reakcio) THEN 'Rendben' ELSE 'Probl�ma' END AS "ki�rt�kel�s"
  FROM (SELECT A.id, A.summary, A.priority, 
               to_timestamp(A.date_submitted) AS date_submitted,
               uno_reakcio(A.id) AS reakcio,
               uno_sla(A.priority::smallint, 0::smallint) AS sla
          FROM mantis_bug_table A
          WHERE date_submitted > 1656626400 --2022.07.01
  ) A
  ORDER BY 1;

--vim:se et shiftwidth=2 fenc=utf-8:
