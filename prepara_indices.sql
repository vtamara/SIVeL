
SET client_encoding = 'LATIN1';

SELECT setval('antecedente_seq', MAX(id)) FROM (SELECT 100 as id UNION SELECT MAX(id) FROM antecedente) AS s;
SELECT setval('caso_seq', max(id)) FROM caso;
sELECT setval('clase_seq', max(id)) FROM clase;
SELECT setval('contexto_seq', MAX(id)) FROM (SELECT 100 as id UNION SELECT MAX(id) FROM contexto) AS s;
SELECT setval('departamento_seq', max(id)) FROM departamento;
SELECT setval('etnia_seq', MAX(id)) FROM (SELECT 2000 as id UNION SELECT MAX(id) FROM etnia) AS s;
SELECT setval('filiacion_seq', MAX(id)) FROM (SELECT 100 as id UNION SELECT MAX(id) FROM filiacion) AS s;
SELECT setval('iglesia_seq', MAX(id)) FROM (SELECT 1000 as id UNION SELECT MAX(id) FROM iglesia) AS s;
SELECT setval('frontera_seq', MAX(id)) FROM (SELECT 100 as id UNION SELECT MAX(id) FROM frontera) AS s;
SELECT setval('fuente_directa_seq', max(id)) FROM fuente_directa;
SELECT setval('funcionario_seq', max(id)) FROM funcionario;
SELECT setval('grupoper_seq', max(id)) FROM grupoper;
SELECT setval('iglesia_seq', MAX(id)) FROM (SELECT 1000 as id UNION SELECT MAX(id) FROM iglesia) AS s;
SELECT setval('intervalo_seq', max(id)) FROM intervalo;
SELECT setval('municipio_seq', max(id)) FROM municipio;
SELECT setval('organizacion_seq', MAX(id)) FROM (SELECT 100 as id UNION SELECT MAX(id) FROM organizacion) AS s;
SELECT setval('parametros_reporte_consolidado_seq', max(no_columna)) FROM parametros_reporte_consolidado;
SELECT setval('persona_seq', max(id)) FROM persona;
SELECT setval('prensa_seq', MAX(id)) FROM (SELECT 100 as id UNION SELECT MAX(id) FROM prensa) AS s;
SELECT setval('presuntos_responsables_seq', MAX(id)) FROM (SELECT 100 as id UNION SELECT MAX(id) FROM presuntos_responsables) AS s;
SELECT setval('profesion_seq', MAX(id)) FROM (SELECT 100 as id UNION SELECT MAX(id) FROM profesion) AS s;
SELECT setval('rango_edad_seq', max(id)) FROM rango_edad;
SELECT setval('region_seq', MAX(id)) FROM (SELECT 100 as id UNION SELECT MAX(id) FROM region) AS s;
SELECT setval('resultado_agresion_seq', max(id)) FROM resultado_agresion;
SELECT setval('rol_seq', max(id_rol)) FROM rol; 
SELECT setval('sector_social_seq', MAX(id)) FROM (SELECT 100 as id UNION SELECT MAX(id) FROM sector_social) AS s;
SELECT setval('tipo_sitio_seq', MAX(id)) FROM (SELECT 100 as id UNION SELECT MAX(id) FROM tipo_sitio) AS s;
SELECT setval('ubicacion_seq', max(id)) FROM ubicacion;
SELECT setval('vinculo_estado_seq', MAX(id)) FROM (SELECT 100 as id UNION SELECT MAX(id) FROM vinculo_estado) AS s;


