PROCEDURE get_AMB_SUBSTATION(out_res            OUT NUMBER, -- код ошибки, если не 0
                  out_rc             OUT SYS_REFCURSOR, -- курсор с данными
                  out_recs           OUT NUMBER, -- общее количество записей
                  out_grps           OUT NUMBER, -- общее количество групп записей
                  in_order           IN VARCHAR2 DEFAULT NULL, -- сортировка по пол€м 
                  in_group           IN VARCHAR2 DEFAULT NULL, -- группировка по пол€м 
                  in_page_size       IN NUMBER DEFAULT 10, -- размер страницы дл€ пейджинга
                  in_page_num        IN NUMBER DEFAULT 1, -- номер страницы пейджинга
                  in_user_login      IN VARCHAR2 DEFAULT NULL, -- пользователь 
                  in_check_access    IN NUMBER DEFAULT NULL, -- учитывать доступ
                  in_amb_substation_ids  IN VARCHAR2 DEFAULT NULL, -- подстанци€
                  in_city_ids        IN VARCHAR2 DEFAULT NULL, -- город
                  in_district_ids    IN VARCHAR2 DEFAULT NULL, -- округ
                  in_search          IN VARCHAR2 DEFAULT NULL) IS 
   
   v_ids       tt_ids_gn_rn;
   v_srt       VARCHAR2(1000);
   v_grp       VARCHAR2(1000);
   v_city_ids  t_ids;
   v_districts t_names;
   v_ac_ids    t_ids;
   v_substation_ids   t_ids;
BEGIN
   out_res := 0;

   v_grp := NVL(in_group, ltrim(in_order || ',SUBSTATION_ID', ','));
   v_srt := NVL(in_order, 'SUBSTATION_ID');
   
   get_ids(in_city_ids, v_city_ids);
   get_names(in_district_ids, v_districts);
   get_ids(in_amb_substation_ids, v_substation_ids);

   -- ѕолучение ID
   EXECUTE IMMEDIATE 'SELECT t_ids_gn_rn(SUBSTATION_ID, dense_rank () over (order by ' || v_grp || '), 
   dense_rank () over (order by ' || v_srt || '))
                        FROM (SELECT DISTINCT s.SUBSTATION_ID, s.SUBSTATION_NAME, s.substation_number
       FROM DCT_AMB_SUBSTATIONS s
                                JOIN dct_districts d
                                  ON d.district_id = s.district_id
                                     AND d.is_cur = 1
                                JOIN dct_cities c
                                  ON c.city_id = d.city_id
                                     AND c.is_cur = 1 ' ||
              CASE WHEN in_district_ids IS NOT NULL THEN 
                               'JOIN TABLE(:v_districts) dis ON dis.column_value = d.district_id ' 
                               ELSE 'JOIN (SELECT :d FROM dual) ON 1 = 1 ' END || 
              CASE WHEN in_amb_substation_ids IS NOT NULL THEN 
                               'JOIN TABLE(:v_substation_ids) al ON al.column_value = s.substation_id ' 
                               ELSE 'JOIN (SELECT :d FROM dual) ON 1 = 1 ' END || 
               CASE WHEN in_city_ids IS NOT NULL THEN 
                                    'AND c.city_id IN (' || in_city_ids || ') ' END || 
              CASE WHEN in_search IS NOT NULL THEN 
                                    'AND (lower(s.SUBSTATION_NAME) LIKE lower(''%'' || :in_search || ''%''))) ' 
                                    ELSE 'AND (1 = 1 OR :in_search IS NULL)) ' END
      BULK COLLECT INTO v_ids USING v_districts, v_substation_ids, in_search;

   -- ѕолучение количества строк и групп
   out_recs := v_ids.count;
   SELECT COUNT(DISTINCT i.gn) INTO out_grps FROM table(v_ids) i;

   -- ѕолучение данных курсора
   OPEN out_rc FOR   
      SELECT  s.SUBSTATION_ID AS AMB_SUBSTATION_ID
      , s.SUBSTATION_NAME AS AMB_SUBSTATION_NAME
      , s.substation_number AS AMB_SUBSTATION_NUMBER
      , SUBSTATION_LAT||','||SUBSTATION_LNG AS AMB_SUBSTATION_GEO
      , s.DISTRICT_ID
      , s.CITY_ID
       FROM DCT_AMB_SUBSTATIONS s
        JOIN TABLE(v_ids) i
          ON i.id = s.SUBSTATION_ID
       WHERE (i.gn BETWEEN ((NVL(in_page_num, 1) - 1) * NVL(in_page_size, 10) + 1) 
       AND (NVL(in_page_num, 1) * NVL(in_page_size, 10)) OR NVL(in_page_size, 10) = -1)
       ORDER BY i.gn, i.rn;
               
EXCEPTION
   WHEN OTHERS THEN
      out_res := SQLCODE;
      dbms_output.put_line(dbms_utility.format_error_backtrace || SQLERRM);
      
END get_AMB_SUBSTATION;