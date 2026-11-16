-- Booking analytics: practice queries on the book_form table.
-- Tested on MariaDB 10.11 (MySQL 8 syntax is the same). Load sample_data.sql first to see results.

-- 1. Monthly bookings and visitors (GROUP BY + aggregates)
SELECT DATE_FORMAT(arrivals, '%Y-%m') AS month,
       COUNT(*)                        AS bookings,
       SUM(visitors)                   AS total_visitors,
       ROUND(AVG(visitors), 1)         AS avg_group_size
FROM book_form
GROUP BY DATE_FORMAT(arrivals, '%Y-%m')
ORDER BY month;

-- 2. Most requested places, with share of all visitors (window function over aggregate)
SELECT location,
       COUNT(*)      AS bookings,
       SUM(visitors) AS visitors,
       ROUND(100 * SUM(visitors) / SUM(SUM(visitors)) OVER (), 1) AS pct_of_visitors
FROM book_form
GROUP BY location
ORDER BY visitors DESC;

-- 3. Group size bands (CASE)
SELECT CASE WHEN visitors <= 2  THEN '1-2 (small)'
            WHEN visitors <= 5  THEN '3-5 (medium)'
            WHEN visitors <= 10 THEN '6-10 (large)'
            ELSE '11+ (tour group)' END AS group_size,
       COUNT(*) AS bookings
FROM book_form
GROUP BY group_size
ORDER BY MIN(visitors);

-- 4. Busiest weekdays
SELECT DAYNAME(arrivals) AS weekday, COUNT(*) AS bookings, SUM(visitors) AS visitors
FROM book_form
GROUP BY DAYNAME(arrivals), DAYOFWEEK(arrivals)
ORDER BY DAYOFWEEK(arrivals);

-- 5. Repeat visitors (HAVING)
SELECT email, COUNT(*) AS bookings, SUM(visitors) AS total_visitors
FROM book_form
GROUP BY email
HAVING COUNT(*) > 1
ORDER BY bookings DESC;

-- 6. Month-over-month change in visitors (CTE + LAG)
WITH monthly AS (
    SELECT DATE_FORMAT(arrivals, '%Y-%m') AS month, SUM(visitors) AS visitors
    FROM book_form
    GROUP BY DATE_FORMAT(arrivals, '%Y-%m')
)
SELECT month,
       visitors,
       visitors - LAG(visitors) OVER (ORDER BY month) AS change_vs_prev,
       ROUND(100 * (visitors - LAG(visitors) OVER (ORDER BY month)) / LAG(visitors) OVER (ORDER BY month), 1) AS pct_change
FROM monthly
ORDER BY month;
