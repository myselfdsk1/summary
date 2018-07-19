CREATE VIEW FCT_TICKETS
AS SELECT s.computation_date AS load_date,
          s.slot_id AS TICKET_ID,
          CAST(s.start_time AS DATE) AS TICKET_START_DATE,
          CAST(s.end_time AS DATE) AS TICKET_END_DATE,
          s.AVAILABLE_RESOURCE_ID,
          s.avail_resource_schedule_id AS SCHEDULE_ID,
          s.complex_resource_id AS complex_resource_id,
          s.is_busy,
          s.is_other_lpu,
          s.is_infomat,
          s.is_cto,
          s.is_internet,
          s.is_recorder,
          s.is_doctor_to_other,
          s.is_doctor_to_self,
          s.is_only_baby,
          s.is_referral,
          la.lpu_id AS LPU_ID,
          s.is_waiting_line,
          s.is_overall,
          CAST(s.end_time AS DATE) - CAST(s.start_time AS DATE) AS AGG_TICKET_DURATION
     FROM emias_data.slot_arch s
     JOIN dct_complex_resources cr
       ON cr.complex_resource_id = s.complex_resource_id
          AND s.computation_date BETWEEN cr.sdate AND cr.edate
     JOIN dct_rooms r
       ON r.room_id = cr.room_id
          AND s.computation_date BETWEEN r.sdate AND r.edate
     JOIN emias_data.ed_lpu_address_arch la
       ON la.id = r.lpu_address_id
          AND la.load_date = s.computation_date;
