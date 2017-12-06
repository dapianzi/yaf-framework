
-- task and subtasks
CREATE VIEW subtasks AS
    SELECT 
        a.id,
        a.adate,
        a.title,
        a.description,
        a.pro_id,
        a.date_start,
        a.date_end,
        b.id sub_task_id,
        b.adate sub_task_adate,
        b.title sub_task_title,
        b.description sub_task_description,
        b.date_start sub_task_date_start,
        b.date_end sub_task_date_end,
        b.task_pid
    FROM
        task a
            LEFT JOIN
        task b ON a.id = b.task_pid
    WHERE
        a.task_pid = 0


-- task info
CREATE VIEW tasks AS
    SELECT
        id,
        adate,
        title,
        description,
        pro_id,
        (CASE
            WHEN sub_task_id IS NULL THEN date_start
            ELSE MIN(sub_task_date_start)
        END) task_start,
        (CASE
            WHEN sub_task_id IS NULL THEN date_end
            ELSE MAX(sub_task_date_end)
        END) task_end,
        COUNT(id) sub_task_count,
        COUNT(DISTINCT id) task_count
    FROM
        subtasks
    GROUP BY id
