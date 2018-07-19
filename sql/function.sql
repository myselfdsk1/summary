create or replace FUNCTION pbdate_query(in_group_period_type IN INTEGER) RETURN VARCHAR2 IS
        v_query VARCHAR2(100);
    BEGIN
        v_query := CASE in_group_period_type
                      when 0   then 'to_date(''19000101'',''yyyymmdd'')'
                      when 1   then 'to_date(YEAR||to_char(MONTH_OF_YEAR,''00'')||DAY_OF_MONTH,''yyyymmdd'')'
                      when 7   then 'to_date(YEAR||''0101'',''yyyymmdd'')+(WEEK_OF_YEAR-1)*7 +1'
                      when 30  then 'to_date(YEAR||to_char(MONTH_OF_YEAR,''00'')||''01'',''yyyymmdd'')'
                      when 90  then 'to_date(YEAR||to_char(QUARTER_OF_YEAR*3-2,''00'')||''01'',''yyyymmdd'')'
                      when 365 then 'to_date(YEAR||''0101'',''yyyymmdd'')'
                   END;

        RETURN v_query;
    END pbdate_query;

