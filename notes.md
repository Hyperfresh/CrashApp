# Compare years
SELECT year FROM `crashdata` WHERE year = ( SELECT MIN(year) FROM `crashdata` )
SELECT year FROM `crashdata` WHERE year = ( SELECT MAX(year) FROM `crashdata` )

# Count drugs only
SELECT * FROM `crashdata` WHERE Drugs = 1

# Most common crash
SELECT collisiontype_id,
COUNT(collisiontype_id) AS `value_occurrence`
FROM `crashdata`
GROUP BY collisiontype_id
ORDER BY `value_occurrence` DESC
LIMIT 2

