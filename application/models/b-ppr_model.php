<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ppr_model extends CI_Model {

	const __TABLE_NAME = 'report';

	/** Return all and filtered data */
	public function filter_all($offset, $limit) {
		$tbl_name = self::__TABLE_NAME;
		$data[] = (int) $offset;
		$data[] = (int) $limit;
		$sql = "SELECT
				*
				FROM(
				SELECT
				cs_id,
				cs_name,
				speed_min,
				total_trans,
				SCORE as ori_score,
				ROUND(SCORE/(
				    SELECT
				    SUM(SCORE)
				    FROM
				    (
				        SELECT
				        CASE
				            WHEN speed_min <= 1 THEN 5*total_trans
				            WHEN speed_min >= 1.1 AND speed_min <= 2 THEN 4*total_trans
				            WHEN speed_min >= 2.1 AND speed_min <= 3 THEN 3*total_trans
				            WHEN speed_min >= 3.1 AND speed_min <= 4 THEN 2*total_trans
				            WHEN speed_min >= 4.1 AND speed_min <= 5 THEN 1*total_trans
				            WHEN speed_min >= 5.1 AND speed_min <= 10 THEN 0.75*total_trans
				            WHEN speed_min > 10.1 THEN 0.25*total_trans
				        END/total_allspeed + total_trans/total_alltrans/2 AS SCORE
				        FROM
				        (
				            SELECT
				            u.`id` as cs_id,
				            u.`complete_name` as cs_name,
				            ROUND(AVG(TIMESTAMPDIFF(SECOND,t.`request_time`,t.`complete_time`) - TIME_TO_SEC(ifnull(t.`pending_time`, 0)))/60, 1) as speed_min,
				            COUNT(t.`id`) as total_trans,
				            (
				                SELECT
				                SUM(total)
				                FROM(
				                    SELECT
				                    COUNT(it.`id`) as total
				                    FROM `transactions` it
				                    INNER JOIN `users` iu ON it.`updated_by` = iu.`id`
				                    WHERE iu.`user_roles_id` = 5
				                    AND it.`status` = 'successful'
				                    AND iu.`id` NOT IN (69, 31, 16, 74, 70)
				                    /*AND iu.`username` NOT LIKE '%tester%'*/
				                    AND iu.`username` NOT LIKE '%trial%'
				                    AND DATE_FORMAT(it.`complete_time`, '%Y-%m-%d') = DATE_FORMAT(NOW(), '%Y-%m-%d')
				                ) as itemp
				            ) as total_alltrans,
				            (
				                SELECT
				                SUM(total_csspeed)
				                FROM(
				                    SELECT 
				                    ROUND(AVG(TIMESTAMPDIFF(SECOND,it.`request_time`,it.`complete_time`) - TIME_TO_SEC(ifnull(it.`pending_time`, 0)))/60, 1) as total_csspeed
				                    FROM `transactions` it
				                    INNER JOIN `users` iu ON it.`updated_by` = iu.`id`
				                    WHERE iu.`user_roles_id` = 5
				                    AND it.`status` = 'successful'
				                    AND iu.`id` NOT IN (69, 31, 16, 74, 70)
				                    /*AND iu.`username` NOT LIKE '%tester%'*/
				                    AND iu.`username` NOT LIKE '%trial%'
				                    AND DATE_FORMAT(it.`complete_time`, '%Y-%m-%d') = DATE_FORMAT(NOW(), '%Y-%m-%d')
				                    GROUP BY iu.`id`
				                ) as itempp
				            ) as total_allspeed
				            FROM `users` u
				            INNER JOIN `transactions` t ON u.`id` = t.`updated_by` 
				            WHERE u.`user_roles_id` = 5
				            AND t.`status` = 'successful'
				            AND u.`id` NOT IN (69, 31, 16, 74, 70)
		                    /*AND u.`username` NOT LIKE '%tester%'*/
		                    AND u.`username` NOT LIKE '%trial%'
				            AND DATE_FORMAT(t.`complete_time`, '%Y-%m-%d') = DATE_FORMAT(NOW(), '%Y-%m-%d')
				            GROUP BY u.`id`
				        ) as temp
				    ) as tmep_1
				)*100, 2) as total_score
				FROM
				(
				    SELECT
				    cs_id,
				    cs_name,
				    speed_min,
				    total_trans,
				    CASE
				        WHEN speed_min <= 1 THEN 5*total_trans
				        WHEN speed_min >= 1.1 AND speed_min <= 2 THEN 4*total_trans
				        WHEN speed_min >= 2.1 AND speed_min <= 3 THEN 3*total_trans
				        WHEN speed_min >= 3.1 AND speed_min <= 4 THEN 2*total_trans
				        WHEN speed_min >= 4.1 AND speed_min <= 5 THEN 1*total_trans
				        WHEN speed_min >= 5.1 AND speed_min <= 10 THEN 0.75*total_trans
				        WHEN speed_min > 10.1 THEN 0.25*total_trans
				    END/total_allspeed + total_trans/total_alltrans/2 AS SCORE
				    FROM
				    (
				        SELECT
				        u.`id` as cs_id,
				        u.`complete_name` as cs_name,
				        ROUND(AVG(TIMESTAMPDIFF(SECOND,t.`request_time`,t.`complete_time`) - TIME_TO_SEC(ifnull(t.`pending_time`, 0)))/60, 1) as speed_min,
				        COUNT(t.`id`) as total_trans,
				        (
				            SELECT
				            SUM(total)
				            FROM(
				                SELECT
				                COUNT(it.`id`) as total
				                FROM `transactions` it
				                INNER JOIN `users` iu ON it.`updated_by` = iu.`id`
				                WHERE iu.`user_roles_id` = 5
				                AND it.`status` = 'successful'
				                AND iu.`id` NOT IN (69, 31, 16, 74, 70)
			                    /*AND iu.`username` NOT LIKE '%tester%'*/
			                    AND iu.`username` NOT LIKE '%trial%'
				                AND DATE_FORMAT(it.`complete_time`, '%Y-%m-%d') = DATE_FORMAT(NOW(), '%Y-%m-%d')
				            ) as itemp
				        ) as total_alltrans,
				        (
				            SELECT
				            SUM(total_csspeed)
				            FROM(
				                SELECT 
				                ROUND(AVG(TIMESTAMPDIFF(SECOND,it.`request_time`,it.`complete_time`) - TIME_TO_SEC(ifnull(it.`pending_time`, 0)))/60, 1) as total_csspeed
				                FROM `transactions` it
				                INNER JOIN `users` iu ON it.`updated_by` = iu.`id`
				                WHERE iu.`user_roles_id` = 5
				                AND it.`status` = 'successful'
				                AND iu.`id` NOT IN (69, 31, 16, 74, 70)
			                    /*AND iu.`username` NOT LIKE '%tester%'*/
			                    AND iu.`username` NOT LIKE '%trial%'
				                AND DATE_FORMAT(it.`complete_time`, '%Y-%m-%d') = DATE_FORMAT(NOW(), '%Y-%m-%d')
				                GROUP BY iu.`id`
				            ) as itempp
				        ) as total_allspeed
				        FROM `users` u
				        INNER JOIN `transactions` t ON u.`id` = t.`updated_by` 
				        WHERE u.`user_roles_id` = 5
				        AND t.`status` = 'successful'
				        AND u.`id` NOT IN (69, 31, 16, 74, 70)
	                    /*AND u.`username` NOT LIKE '%tester%'*/
	                    AND u.`username` NOT LIKE '%trial%'

				        AND DATE_FORMAT(t.`complete_time`, '%Y-%m-%d') = DATE_FORMAT(NOW(), '%Y-%m-%d')
				        GROUP BY u.`id`
				    ) as temp
				) as ftemp
				GROUP BY cs_id
				) as ppr
				ORDER BY total_score DESC
				LIMIT 20";
		$query = $this->db->query($sql, $data);
		$result = $query->result();
		/*echo $this->db->last_query();*/
		return $result;
	}

/*	public function filter_lastday($offset, $limit) {
		$tbl_name = self::__TABLE_NAME;
		$data[] = (int) $offset;
		$data[] = (int) $limit;
		$sql = "SELECT
				*
				FROM(
				SELECT
				cs_id,
				cs_name,
				speed_min,
				total_trans,
				ROUND(SCORE/(
				    SELECT
				    SUM(SCORE)
				    FROM
				    (
				        SELECT
				        CASE
				            WHEN speed_min <= 1 THEN speed_min*5*total_trans
				            WHEN speed_min >= 1.1 AND speed_min <= 2 THEN speed_min*4*total_trans
				            WHEN speed_min >= 2.1 AND speed_min <= 3 THEN speed_min*3*total_trans
				            WHEN speed_min >= 3.1 AND speed_min <= 4 THEN speed_min*2*total_trans
				            WHEN speed_min >= 4.1 AND speed_min <= 5 THEN speed_min*1*total_trans
				            WHEN speed_min >= 5.1 AND speed_min <= 10 THEN   (total_trans*speed_min )-(speed_min*1.25)
				            WHEN speed_min > 10.1 THEN   (total_trans*speed_min )-(speed_min*1.75)
				        END/total_allspeed + total_trans/total_alltrans/2 AS SCORE
				        FROM
				        (
				            SELECT
				            u.`id` as cs_id,
				            u.`complete_name` as cs_name,
				            ROUND(AVG(TIMESTAMPDIFF(SECOND,t.`request_time`,t.`complete_time`) - TIME_TO_SEC(ifnull(t.`pending_time`, 0)))/60, 1) as speed_min,
				            COUNT(t.`id`) as total_trans,
				            (
				                SELECT
				                SUM(total)
				                FROM(
				                    SELECT
				                    COUNT(it.`id`) as total
				                    FROM `transactions` it
				                    INNER JOIN `users` iu ON it.`updated_by` = iu.`id`
				                    WHERE iu.`user_roles_id` = 5
				                    AND it.`status` = 'successful'
				                    AND iu.`id` NOT IN (69, 31, 16, 74, 70)
				                    AND iu.`username` NOT LIKE '%tester%'
				                    AND iu.`username` NOT LIKE '%trial%'
				                    AND DATE_FORMAT(it.`complete_time`, '%Y-%m-%d') = '2017-05-01'
				                ) as itemp
				            ) as total_alltrans,
				            (
				                SELECT
				                SUM(total_csspeed)
				                FROM(
				                    SELECT 
				                    ROUND(AVG(TIMESTAMPDIFF(SECOND,it.`request_time`,it.`complete_time`) - TIME_TO_SEC(ifnull(it.`pending_time`, 0)))/60, 1) as total_csspeed
				                    FROM `transactions` it
				                    INNER JOIN `users` iu ON it.`updated_by` = iu.`id`
				                    WHERE iu.`user_roles_id` = 5
				                    AND it.`status` = 'successful'
				                    AND iu.`id` NOT IN (69, 31, 16, 74, 70)
				                    AND iu.`username` NOT LIKE '%tester%'
				                    AND iu.`username` NOT LIKE '%trial%'
				                    AND DATE_FORMAT(it.`complete_time`, '%Y-%m-%d') = '2017-05-01'
				                    GROUP BY iu.`id`
				                ) as itempp
				            ) as total_allspeed
				            FROM `users` u
				            INNER JOIN `transactions` t ON u.`id` = t.`updated_by` 
				            WHERE u.`user_roles_id` = 5
				            AND t.`status` = 'successful'
				            AND u.`id` NOT IN (69, 31, 16, 74, 70)
		                    AND u.`username` NOT LIKE '%tester%'
		                    AND u.`username` NOT LIKE '%trial%'
				            AND DATE_FORMAT(t.`complete_time`, '%Y-%m-%d') = '2017-05-01'
				            GROUP BY u.`id`
				        ) as temp
				    ) as tmep_1
				)*100, 2) as total_score
				FROM
				(
				    SELECT
				    cs_id,
				    cs_name,
				    speed_min,
				    total_trans,
				    CASE
				        WHEN speed_min <= 1 THEN speed_min*5*total_trans
				        WHEN speed_min >= 1.1 AND speed_min <= 2 THEN speed_min*4*total_trans
				        WHEN speed_min >= 2.1 AND speed_min <= 3 THEN speed_min*3*total_trans
				        WHEN speed_min >= 3.1 AND speed_min <= 4 THEN speed_min*2*total_trans
				        WHEN speed_min >= 4.1 AND speed_min <= 5 THEN speed_min*1*total_trans
				        WHEN speed_min >= 5.1 AND speed_min <= 10 THEN   (total_trans*speed_min )-(speed_min*1.25)
				        WHEN speed_min > 10.1 THEN   (total_trans*speed_min )-(speed_min*1.75)
				    END/total_allspeed + total_trans/total_alltrans/2 AS SCORE
				    FROM
				    (
				        SELECT
				        u.`id` as cs_id,
				        u.`complete_name` as cs_name,
				        ROUND(AVG(TIMESTAMPDIFF(SECOND,t.`request_time`,t.`complete_time`) - TIME_TO_SEC(ifnull(t.`pending_time`, 0)))/60, 1) as speed_min,
				        COUNT(t.`id`) as total_trans,
				        (
				            SELECT
				            SUM(total)
				            FROM(
				                SELECT
				                COUNT(it.`id`) as total
				                FROM `transactions` it
				                INNER JOIN `users` iu ON it.`updated_by` = iu.`id`
				                WHERE iu.`user_roles_id` = 5
				                AND it.`status` = 'successful'
				                AND iu.`id` NOT IN (69, 31, 16, 74, 70)
			                    AND iu.`username` NOT LIKE '%tester%'
			                    AND iu.`username` NOT LIKE '%trial%'
				                AND DATE_FORMAT(it.`complete_time`, '%Y-%m-%d') = '2017-05-01'
				            ) as itemp
				        ) as total_alltrans,
				        (
				            SELECT
				            SUM(total_csspeed)
				            FROM(
				                SELECT 
				                ROUND(AVG(TIMESTAMPDIFF(SECOND,it.`request_time`,it.`complete_time`) - TIME_TO_SEC(ifnull(it.`pending_time`, 0)))/60, 1) as total_csspeed
				                FROM `transactions` it
				                INNER JOIN `users` iu ON it.`updated_by` = iu.`id`
				                WHERE iu.`user_roles_id` = 5
				                AND it.`status` = 'successful'
				                AND iu.`id` NOT IN (69, 31, 16, 74, 70)
			                    AND iu.`username` NOT LIKE '%tester%'
			                    AND iu.`username` NOT LIKE '%trial%'
				                AND DATE_FORMAT(it.`complete_time`, '%Y-%m-%d') = '2017-05-01'
				                GROUP BY iu.`id`
				            ) as itempp
				        ) as total_allspeed
				        FROM `users` u
				        INNER JOIN `transactions` t ON u.`id` = t.`updated_by` 
				        WHERE u.`user_roles_id` = 5
				        AND t.`status` = 'successful'
				        AND u.`id` NOT IN (69, 31, 16, 74, 70)
	                    AND u.`username` NOT LIKE '%tester%'
	                    AND u.`username` NOT LIKE '%trial%'

				        AND DATE_FORMAT(t.`complete_time`, '%Y-%m-%d') = '2017-05-01'
				        GROUP BY u.`id`
				    ) as temp
				) as ftemp
				GROUP BY cs_id
				) as ppr
				ORDER BY total_score DESC
				LIMIT 20";
		$query = $this->db->query($sql, $data);
		$result = $query->result();
		return $result;
	}
*/
	public function top_1stweek() {
		$data = array();
		$sql = "SELECT
				*
				FROM(
				SELECT
				cs_name,
				ROUND(SCORE/(
				    SELECT
				    SUM(SCORE)
				    FROM
				    (
				        SELECT
				        CASE
				            WHEN speed_min <= 1 THEN 5*total_trans
				            WHEN speed_min >= 1.1 AND speed_min <= 2 THEN 4*total_trans
				            WHEN speed_min >= 2.1 AND speed_min <= 3 THEN 3*total_trans
				            WHEN speed_min >= 3.1 AND speed_min <= 4 THEN 2*total_trans
				            WHEN speed_min >= 4.1 AND speed_min <= 5 THEN 1*total_trans
				            WHEN speed_min >= 5.1 AND speed_min <= 10 THEN 0.75*total_trans
				            WHEN speed_min > 10.1 THEN 0.25*total_trans
				        END/total_allspeed + total_trans/total_alltrans/2 AS SCORE
				        FROM
				        (
				            SELECT
				            u.`id` as cs_id,
				            u.`complete_name` as cs_name,
				            ROUND(AVG(TIMESTAMPDIFF(SECOND,t.`request_time`,t.`complete_time`) - TIME_TO_SEC(ifnull(t.`pending_time`, 0)))/60, 1) as speed_min,
				            COUNT(t.`id`) as total_trans,
				            (
				                SELECT
				                SUM(total)
				                FROM(
				                    SELECT
				                    COUNT(it.`id`) as total
				                    FROM `transactions` it
				                    INNER JOIN `users` iu ON it.`updated_by` = iu.`id`
				                    WHERE iu.`user_roles_id` = 5
				                    AND it.`status` = 'successful'
				                    AND iu.`id` NOT IN (69, 31, 16, 74, 70)
				                    AND iu.`username` NOT LIKE '%tester%'
				                    AND iu.`username` NOT LIKE '%trial%'
				                    AND DATE_FORMAT(it.`complete_time`, '%Y-%m-%d') 
						                BETWEEN DATE_SUB(DATE(NOW()), INTERVAL DAYOFWEEK(NOW())+5 DAY)
										AND DATE_SUB(DATE(NOW()), INTERVAL DAYOFWEEK(NOW())-1 DAY)
				                ) as itemp
				            ) as total_alltrans,
				            (
				                SELECT
				                SUM(total_csspeed)
				                FROM(
				                    SELECT 
				                    ROUND(AVG(TIMESTAMPDIFF(SECOND,it.`request_time`,it.`complete_time`) - TIME_TO_SEC(ifnull(it.`pending_time`, 0)))/60, 1) as total_csspeed
				                    FROM `transactions` it
				                    INNER JOIN `users` iu ON it.`updated_by` = iu.`id`
				                    WHERE iu.`user_roles_id` = 5
				                    AND it.`status` = 'successful'
				                    AND iu.`id` NOT IN (69, 31, 16, 74, 70)
				                    AND iu.`username` NOT LIKE '%tester%'
				                    AND iu.`username` NOT LIKE '%trial%'
				                    AND DATE_FORMAT(it.`complete_time`, '%Y-%m-%d') 
						                BETWEEN DATE_SUB(DATE(NOW()), INTERVAL DAYOFWEEK(NOW())+5 DAY)
										AND DATE_SUB(DATE(NOW()), INTERVAL DAYOFWEEK(NOW())-1 DAY)
				                    GROUP BY iu.`id`
				                ) as itempp
				            ) as total_allspeed
				            FROM `users` u
				            INNER JOIN `transactions` t ON u.`id` = t.`updated_by` 
				            WHERE u.`user_roles_id` = 5
				            AND t.`status` = 'successful'
				            AND u.`id` NOT IN (69, 31, 16, 74, 70)
		                    AND u.`username` NOT LIKE '%tester%'
		                    AND u.`username` NOT LIKE '%trial%'
				            AND DATE_FORMAT(t.`complete_time`, '%Y-%m-%d')
				                BETWEEN DATE_SUB(DATE(NOW()), INTERVAL DAYOFWEEK(NOW())+5 DAY)
								AND DATE_SUB(DATE(NOW()), INTERVAL DAYOFWEEK(NOW())-1 DAY)
				            GROUP BY u.`id`
				        ) as temp
				    ) as tmep_1
				)*100, 2) as total_score
				FROM
				(
				    SELECT
				    cs_id,
				    cs_name,
				    speed_min,
				    total_trans,
				    CASE
				        WHEN speed_min <= 1 THEN 5*total_trans
				        WHEN speed_min >= 1.1 AND speed_min <= 2 THEN 4*total_trans
				        WHEN speed_min >= 2.1 AND speed_min <= 3 THEN 3*total_trans
				        WHEN speed_min >= 3.1 AND speed_min <= 4 THEN 2*total_trans
				        WHEN speed_min >= 4.1 AND speed_min <= 5 THEN 1*total_trans
				        WHEN speed_min >= 5.1 AND speed_min <= 10 THEN 0.75*total_trans
				        WHEN speed_min > 10.1 THEN 0.25*total_trans
				    END/total_allspeed + total_trans/total_alltrans/2 AS SCORE
				    FROM
				    (
				        SELECT
				        u.`id` as cs_id,
				        u.`complete_name` as cs_name,
				        ROUND(AVG(TIMESTAMPDIFF(SECOND,t.`request_time`,t.`complete_time`) - TIME_TO_SEC(ifnull(t.`pending_time`, 0)))/60, 1) as speed_min,
				        COUNT(t.`id`) as total_trans,
				        (
				            SELECT
				            SUM(total)
				            FROM(
				                SELECT
				                COUNT(it.`id`) as total
				                FROM `transactions` it
				                INNER JOIN `users` iu ON it.`updated_by` = iu.`id`
				                WHERE iu.`user_roles_id` = 5
				                AND it.`status` = 'successful'
				                AND iu.`id` NOT IN (69, 31, 16, 74, 70)
			                    AND iu.`username` NOT LIKE '%tester%'
			                    AND iu.`username` NOT LIKE '%trial%'
				                AND DATE_FORMAT(it.`complete_time`, '%Y-%m-%d') 
					                BETWEEN DATE_SUB(DATE(NOW()), INTERVAL DAYOFWEEK(NOW())+5 DAY)
									AND DATE_SUB(DATE(NOW()), INTERVAL DAYOFWEEK(NOW())-1 DAY)
				            ) as itemp
				        ) as total_alltrans,
				        (
				            SELECT
				            SUM(total_csspeed)
				            FROM(
				                SELECT 
				                ROUND(AVG(TIMESTAMPDIFF(SECOND,it.`request_time`,it.`complete_time`) - TIME_TO_SEC(ifnull(it.`pending_time`, 0)))/60, 1) as total_csspeed
				                FROM `transactions` it
				                INNER JOIN `users` iu ON it.`updated_by` = iu.`id`
				                WHERE iu.`user_roles_id` = 5
				                AND it.`status` = 'successful'
				                AND iu.`id` NOT IN (69, 31, 16, 74, 70)
			                    AND iu.`username` NOT LIKE '%tester%'
			                    AND iu.`username` NOT LIKE '%trial%'
				                AND DATE_FORMAT(it.`complete_time`, '%Y-%m-%d') 
					                BETWEEN DATE_SUB(DATE(NOW()), INTERVAL DAYOFWEEK(NOW())+5 DAY)
									AND DATE_SUB(DATE(NOW()), INTERVAL DAYOFWEEK(NOW())-1 DAY)
				                GROUP BY iu.`id`
				            ) as itempp
				        ) as total_allspeed
				        FROM `users` u
				        INNER JOIN `transactions` t ON u.`id` = t.`updated_by` 
				        WHERE u.`user_roles_id` = 5
				        AND t.`status` = 'successful'
				        AND u.`id` NOT IN (69, 31, 16, 74, 70)
	                    AND u.`username` NOT LIKE '%tester%'
	                    AND u.`username` NOT LIKE '%trial%'
				        AND DATE_FORMAT(t.`complete_time`, '%Y-%m-%d') 
			                BETWEEN DATE_SUB(DATE(NOW()), INTERVAL DAYOFWEEK(NOW())+5 DAY)
							AND DATE_SUB(DATE(NOW()), INTERVAL DAYOFWEEK(NOW())-1 DAY)
				        GROUP BY u.`id`
				    ) as temp
				) as ftemp
				GROUP BY cs_id
				) as ppr
				ORDER BY total_score DESC
				LIMIT 3";
		$query = $this->db->query($sql, $data);
		$result = $query->result();
		return $result;	    
	}

	public function top_2ndweek() {
		$data = array();
		$sql = "SELECT
				*
				FROM(
				SELECT
				cs_name,
				ROUND(SCORE/(
				    SELECT
				    SUM(SCORE)
				    FROM
				    (
				        SELECT
				        CASE
				            WHEN speed_min <= 1 THEN 5*total_trans
				            WHEN speed_min >= 1.1 AND speed_min <= 2 THEN 4*total_trans
				            WHEN speed_min >= 2.1 AND speed_min <= 3 THEN 3*total_trans
				            WHEN speed_min >= 3.1 AND speed_min <= 4 THEN 2*total_trans
				            WHEN speed_min >= 4.1 AND speed_min <= 5 THEN 1*total_trans
				            WHEN speed_min >= 5.1 AND speed_min <= 10 THEN 0.75*total_trans
				            WHEN speed_min > 10.1 THEN 0.25*total_trans
				        END/total_allspeed + total_trans/total_alltrans/2 AS SCORE
				        FROM
				        (
				            SELECT
				            u.`id` as cs_id,
				            u.`complete_name` as cs_name,
				            ROUND(AVG(TIMESTAMPDIFF(SECOND,t.`request_time`,t.`complete_time`) - TIME_TO_SEC(ifnull(t.`pending_time`, 0)))/60, 1) as speed_min,
				            COUNT(t.`id`) as total_trans,
				            (
				                SELECT
				                SUM(total)
				                FROM(
				                    SELECT
				                    COUNT(it.`id`) as total
				                    FROM `transactions` it
				                    INNER JOIN `users` iu ON it.`updated_by` = iu.`id`
				                    WHERE iu.`user_roles_id` = 5
				                    AND it.`status` = 'successful'
				                    AND iu.`id` NOT IN (69, 31, 16, 74, 70)
				                    AND iu.`username` NOT LIKE '%tester%'
				                    AND iu.`username` NOT LIKE '%trial%'
				                    AND DATE_FORMAT(it.`complete_time`, '%Y-%m-%d')
						                BETWEEN DATE_SUB(DATE(NOW()), INTERVAL DAYOFWEEK(NOW())+14 DAY)
										AND DATE_SUB(DATE(NOW()), INTERVAL DAYOFWEEK(NOW())+6 DAY)
				                ) as itemp
				            ) as total_alltrans,
				            (
				                SELECT
				                SUM(total_csspeed)
				                FROM(
				                    SELECT 
				                    ROUND(AVG(TIMESTAMPDIFF(SECOND,it.`request_time`,it.`complete_time`) - TIME_TO_SEC(ifnull(it.`pending_time`, 0)))/60, 1) as total_csspeed
				                    FROM `transactions` it
				                    INNER JOIN `users` iu ON it.`updated_by` = iu.`id`
				                    WHERE iu.`user_roles_id` = 5
				                    AND it.`status` = 'successful'
				                    AND iu.`id` NOT IN (69, 31, 16, 74, 70)
				                    AND iu.`username` NOT LIKE '%tester%'
				                    AND iu.`username` NOT LIKE '%trial%'
				                    AND DATE_FORMAT(it.`complete_time`, '%Y-%m-%d')
						                BETWEEN DATE_SUB(DATE(NOW()), INTERVAL DAYOFWEEK(NOW())+14 DAY)
										AND DATE_SUB(DATE(NOW()), INTERVAL DAYOFWEEK(NOW())+6 DAY)
				                    GROUP BY iu.`id`
				                ) as itempp
				            ) as total_allspeed
				            FROM `users` u
				            INNER JOIN `transactions` t ON u.`id` = t.`updated_by` 
				            WHERE u.`user_roles_id` = 5
				            AND t.`status` = 'successful'
				            AND u.`id` NOT IN (69, 31, 16, 74, 70)
		                    AND u.`username` NOT LIKE '%tester%'
		                    AND u.`username` NOT LIKE '%trial%'
				            AND DATE_FORMAT(t.`complete_time`, '%Y-%m-%d')
				                BETWEEN DATE_SUB(DATE(NOW()), INTERVAL DAYOFWEEK(NOW())+14 DAY)
								AND DATE_SUB(DATE(NOW()), INTERVAL DAYOFWEEK(NOW())+6 DAY)
				            GROUP BY u.`id`
				        ) as temp
				    ) as tmep_1
				)*100, 2) as total_score
				FROM
				(
				    SELECT
				    cs_id,
				    cs_name,
				    speed_min,
				    total_trans,
				    CASE
				        WHEN speed_min <= 1 THEN 5*total_trans
				        WHEN speed_min >= 1.1 AND speed_min <= 2 THEN 4*total_trans
				        WHEN speed_min >= 2.1 AND speed_min <= 3 THEN 3*total_trans
				        WHEN speed_min >= 3.1 AND speed_min <= 4 THEN 2*total_trans
				        WHEN speed_min >= 4.1 AND speed_min <= 5 THEN 1*total_trans
				        WHEN speed_min >= 5.1 AND speed_min <= 10 THEN 0.75*total_trans
				        WHEN speed_min > 10.1 THEN 0.25*total_trans
				    END/total_allspeed + total_trans/total_alltrans/2 AS SCORE
				    FROM
				    (
				        SELECT
				        u.`id` as cs_id,
				        u.`complete_name` as cs_name,
				        ROUND(AVG(TIMESTAMPDIFF(SECOND,t.`request_time`,t.`complete_time`) - TIME_TO_SEC(ifnull(t.`pending_time`, 0)))/60, 1) as speed_min,
				        COUNT(t.`id`) as total_trans,
				        (
				            SELECT
				            SUM(total)
				            FROM(
				                SELECT
				                COUNT(it.`id`) as total
				                FROM `transactions` it
				                INNER JOIN `users` iu ON it.`updated_by` = iu.`id`
				                WHERE iu.`user_roles_id` = 5
				                AND it.`status` = 'successful'
				                AND iu.`id` NOT IN (69, 31, 16, 74, 70)
			                    AND iu.`username` NOT LIKE '%tester%'
			                    AND iu.`username` NOT LIKE '%trial%'
				                AND DATE_FORMAT(it.`complete_time`, '%Y-%m-%d')
					                BETWEEN DATE_SUB(DATE(NOW()), INTERVAL DAYOFWEEK(NOW())+14 DAY)
									AND DATE_SUB(DATE(NOW()), INTERVAL DAYOFWEEK(NOW())+6 DAY)
				            ) as itemp
				        ) as total_alltrans,
				        (
				            SELECT
				            SUM(total_csspeed)
				            FROM(
				                SELECT 
				                ROUND(AVG(TIMESTAMPDIFF(SECOND,it.`request_time`,it.`complete_time`) - TIME_TO_SEC(ifnull(it.`pending_time`, 0)))/60, 1) as total_csspeed
				                FROM `transactions` it
				                INNER JOIN `users` iu ON it.`updated_by` = iu.`id`
				                WHERE iu.`user_roles_id` = 5
				                AND it.`status` = 'successful'
				                AND iu.`id` NOT IN (69, 31, 16, 74, 70)
			                    AND iu.`username` NOT LIKE '%tester%'
			                    AND iu.`username` NOT LIKE '%trial%'
				                AND DATE_FORMAT(it.`complete_time`, '%Y-%m-%d')
					                BETWEEN DATE_SUB(DATE(NOW()), INTERVAL DAYOFWEEK(NOW())+14 DAY)
									AND DATE_SUB(DATE(NOW()), INTERVAL DAYOFWEEK(NOW())+6 DAY)
				                GROUP BY iu.`id`
				            ) as itempp
				        ) as total_allspeed
				        FROM `users` u
				        INNER JOIN `transactions` t ON u.`id` = t.`updated_by` 
				        WHERE u.`user_roles_id` = 5
				        AND t.`status` = 'successful'
				        AND u.`id` NOT IN (69, 31, 16, 74, 70)
	                    AND u.`username` NOT LIKE '%tester%'
	                    AND u.`username` NOT LIKE '%trial%'
				        AND DATE_FORMAT(t.`complete_time`, '%Y-%m-%d')
			                BETWEEN DATE_SUB(DATE(NOW()), INTERVAL DAYOFWEEK(NOW())+14 DAY)
							AND DATE_SUB(DATE(NOW()), INTERVAL DAYOFWEEK(NOW())+6 DAY)
				        GROUP BY u.`id`
				    ) as temp
				) as ftemp
				GROUP BY cs_id
				) as ppr
				ORDER BY total_score DESC
				LIMIT 3";
		$query = $this->db->query($sql, $data);
		$result = $query->result();
		return $result;	    
	}

	public function poor_1stweek() {
		$data = array();
		$sql = "SELECT
				*
				FROM(
				SELECT
				cs_name,
				ROUND(SCORE/(
				    SELECT
				    SUM(SCORE)
				    FROM
				    (
				        SELECT
				        CASE
				            WHEN speed_min <= 1 THEN 5*total_trans
				            WHEN speed_min >= 1.1 AND speed_min <= 2 THEN 4*total_trans
				            WHEN speed_min >= 2.1 AND speed_min <= 3 THEN 3*total_trans
				            WHEN speed_min >= 3.1 AND speed_min <= 4 THEN 2*total_trans
				            WHEN speed_min >= 4.1 AND speed_min <= 5 THEN 1*total_trans
				            WHEN speed_min >= 5.1 AND speed_min <= 10 THEN 0.75*total_trans
				            WHEN speed_min > 10.1 THEN 0.25*total_trans
				        END/total_allspeed + total_trans/total_alltrans/2 AS SCORE
				        FROM
				        (
				            SELECT
				            u.`id` as cs_id,
				            u.`complete_name` as cs_name,
				            ROUND(AVG(TIMESTAMPDIFF(SECOND,t.`request_time`,t.`complete_time`) - TIME_TO_SEC(ifnull(t.`pending_time`, 0)))/60, 1) as speed_min,
				            COUNT(t.`id`) as total_trans,
				            (
				                SELECT
				                SUM(total)
				                FROM(
				                    SELECT
				                    COUNT(it.`id`) as total
				                    FROM `transactions` it
				                    INNER JOIN `users` iu ON it.`updated_by` = iu.`id`
				                    WHERE iu.`user_roles_id` = 5
				                    AND it.`status` = 'successful'
                                    AND iu.`id` NOT IN (69, 31, 16, 74, 70)
				                    AND iu.`username` NOT LIKE '%tester%'
				                    AND iu.`username` NOT LIKE '%trial%'
				                    AND DATE_FORMAT(it.`complete_time`, '%Y-%m-%d') 
						                BETWEEN DATE_SUB(DATE(NOW()), INTERVAL DAYOFWEEK(NOW())+5 DAY)
										AND DATE_SUB(DATE(NOW()), INTERVAL DAYOFWEEK(NOW())-1 DAY)
				                ) as itemp
				            ) as total_alltrans,
				            (
				                SELECT
				                SUM(total_csspeed)
				                FROM(
				                    SELECT 
				                    ROUND(AVG(TIMESTAMPDIFF(SECOND,it.`request_time`,it.`complete_time`) - TIME_TO_SEC(ifnull(it.`pending_time`, 0)))/60, 1) as total_csspeed
				                    FROM `transactions` it
				                    INNER JOIN `users` iu ON it.`updated_by` = iu.`id`
				                    WHERE iu.`user_roles_id` = 5
				                    AND it.`status` = 'successful'
                                    AND iu.`id` NOT IN (69, 31, 16, 74, 70)
				                    AND iu.`username` NOT LIKE '%tester%'
				                    AND iu.`username` NOT LIKE '%trial%'
				                    AND DATE_FORMAT(it.`complete_time`, '%Y-%m-%d') 
						                BETWEEN DATE_SUB(DATE(NOW()), INTERVAL DAYOFWEEK(NOW())+5 DAY)
										AND DATE_SUB(DATE(NOW()), INTERVAL DAYOFWEEK(NOW())-1 DAY)
				                    GROUP BY iu.`id`
				                ) as itempp
				            ) as total_allspeed
				            FROM `users` u
				            INNER JOIN `transactions` t ON u.`id` = t.`updated_by` 
				            WHERE u.`user_roles_id` = 5
				            AND t.`status` = 'successful'
                            AND u.`id` NOT IN (69, 31, 16, 74, 70)
		                    AND u.`username` NOT LIKE '%tester%'
		                    AND u.`username` NOT LIKE '%trial%'
				            AND DATE_FORMAT(t.`complete_time`, '%Y-%m-%d') 
				                BETWEEN DATE_SUB(DATE(NOW()), INTERVAL DAYOFWEEK(NOW())+5 DAY)
								AND DATE_SUB(DATE(NOW()), INTERVAL DAYOFWEEK(NOW())-1 DAY)
				            GROUP BY u.`id`
				        ) as temp
				    ) as tmep_1
				)*100, 2) as total_score
				FROM
				(
				    SELECT
				    cs_id,
				    cs_name,
				    speed_min,
				    total_trans,
				    CASE
				        WHEN speed_min <= 1 THEN 5*total_trans
				        WHEN speed_min >= 1.1 AND speed_min <= 2 THEN 4*total_trans
				        WHEN speed_min >= 2.1 AND speed_min <= 3 THEN 3*total_trans
				        WHEN speed_min >= 3.1 AND speed_min <= 4 THEN 2*total_trans
				        WHEN speed_min >= 4.1 AND speed_min <= 5 THEN 1*total_trans
				        WHEN speed_min >= 5.1 AND speed_min <= 10 THEN 0.75*total_trans
				        WHEN speed_min > 10.1 THEN 0.25*total_trans
				    END/total_allspeed + total_trans/total_alltrans/2 AS SCORE
				    FROM
				    (
				        SELECT
				        u.`id` as cs_id,
				        u.`complete_name` as cs_name,
				        ROUND(AVG(TIMESTAMPDIFF(SECOND,t.`request_time`,t.`complete_time`) - TIME_TO_SEC(ifnull(t.`pending_time`, 0)))/60, 1) as speed_min,
				        COUNT(t.`id`) as total_trans,
				        (
				            SELECT
				            SUM(total)
				            FROM(
				                SELECT
				                COUNT(it.`id`) as total
				                FROM `transactions` it
				                INNER JOIN `users` iu ON it.`updated_by` = iu.`id`
				                WHERE iu.`user_roles_id` = 5
				                AND it.`status` = 'successful'
                                AND iu.`id` NOT IN (69, 31, 16, 74, 70)
			                    AND iu.`username` NOT LIKE '%tester%'
			                    AND iu.`username` NOT LIKE '%trial%'
				                AND DATE_FORMAT(it.`complete_time`, '%Y-%m-%d') 
					                BETWEEN DATE_SUB(DATE(NOW()), INTERVAL DAYOFWEEK(NOW())+5 DAY)
									AND DATE_SUB(DATE(NOW()), INTERVAL DAYOFWEEK(NOW())-1 DAY)
				            ) as itemp
				        ) as total_alltrans,
				        (
				            SELECT
				            SUM(total_csspeed)
				            FROM(
				                SELECT 
				                ROUND(AVG(TIMESTAMPDIFF(SECOND,it.`request_time`,it.`complete_time`) - TIME_TO_SEC(ifnull(it.`pending_time`, 0)))/60, 1) as total_csspeed
				                FROM `transactions` it
				                INNER JOIN `users` iu ON it.`updated_by` = iu.`id`
				                WHERE iu.`user_roles_id` = 5
				                AND it.`status` = 'successful'
                                AND iu.`id` NOT IN (69, 31, 16, 74, 70)
			                    AND iu.`username` NOT LIKE '%tester%'
			                    AND iu.`username` NOT LIKE '%trial%'
				                AND DATE_FORMAT(it.`complete_time`, '%Y-%m-%d') 
					                BETWEEN DATE_SUB(DATE(NOW()), INTERVAL DAYOFWEEK(NOW())+5 DAY)
									AND DATE_SUB(DATE(NOW()), INTERVAL DAYOFWEEK(NOW())-1 DAY)
				                GROUP BY iu.`id`
				            ) as itempp
				        ) as total_allspeed
				        FROM `users` u
				        INNER JOIN `transactions` t ON u.`id` = t.`updated_by` 
				        WHERE u.`user_roles_id` = 5
				        AND t.`status` = 'successful'
                        AND u.`id` NOT IN (69, 31, 16, 74, 70)
	                    AND u.`username` NOT LIKE '%tester%'
	                    AND u.`username` NOT LIKE '%trial%'
				        AND DATE_FORMAT(t.`complete_time`, '%Y-%m-%d') 
			                BETWEEN DATE_SUB(DATE(NOW()), INTERVAL DAYOFWEEK(NOW())+5 DAY)
							AND DATE_SUB(DATE(NOW()), INTERVAL DAYOFWEEK(NOW())-1 DAY)
				        GROUP BY u.`id`
				    ) as temp
				) as ftemp
				GROUP BY cs_id
				) as ppr
				ORDER BY total_score ASC
				LIMIT 3";
		$query = $this->db->query($sql, $data);
		$result = $query->result();
		return $result;	  
	}

	public function poor_2ndweek() {
		$data = array();
		$sql = "SELECT
				*
				FROM(
				SELECT
				cs_name,
				ROUND(SCORE/(
				    SELECT
				    SUM(SCORE)
				    FROM
				    (
				        SELECT
				        CASE
				            WHEN speed_min <= 1 THEN 5*total_trans
				            WHEN speed_min >= 1.1 AND speed_min <= 2 THEN 4*total_trans
				            WHEN speed_min >= 2.1 AND speed_min <= 3 THEN 3*total_trans
				            WHEN speed_min >= 3.1 AND speed_min <= 4 THEN 2*total_trans
				            WHEN speed_min >= 4.1 AND speed_min <= 5 THEN 1*total_trans
				            WHEN speed_min >= 5.1 AND speed_min <= 10 THEN 0.75*total_trans
				            WHEN speed_min > 10.1 THEN 0.25*total_trans
				        END/total_allspeed + total_trans/total_alltrans/2 AS SCORE
				        FROM
				        (
				            SELECT
				            u.`id` as cs_id,
				            u.`complete_name` as cs_name,
				            ROUND(AVG(TIMESTAMPDIFF(SECOND,t.`request_time`,t.`complete_time`) - TIME_TO_SEC(ifnull(t.`pending_time`, 0)))/60, 1) as speed_min,
				            COUNT(t.`id`) as total_trans,
				            (
				                SELECT
				                SUM(total)
				                FROM(
				                    SELECT
				                    COUNT(it.`id`) as total
				                    FROM `transactions` it
				                    INNER JOIN `users` iu ON it.`updated_by` = iu.`id`
				                    WHERE iu.`user_roles_id` = 5
				                    AND it.`status` = 'successful'
                                    AND iu.`id` NOT IN (69, 31, 16, 74, 70)
				                    AND iu.`username` NOT LIKE '%tester%'
				                    AND iu.`username` NOT LIKE '%trial%'
				                    AND DATE_FORMAT(it.`complete_time`, '%Y-%m-%d')
						                BETWEEN DATE_SUB(DATE(NOW()), INTERVAL DAYOFWEEK(NOW())+14 DAY)
										AND DATE_SUB(DATE(NOW()), INTERVAL DAYOFWEEK(NOW())+6 DAY)
				                ) as itemp
				            ) as total_alltrans,
				            (
				                SELECT
				                SUM(total_csspeed)
				                FROM(
				                    SELECT 
				                    ROUND(AVG(TIMESTAMPDIFF(SECOND,it.`request_time`,it.`complete_time`) - TIME_TO_SEC(ifnull(it.`pending_time`, 0)))/60, 1) as total_csspeed
				                    FROM `transactions` it
				                    INNER JOIN `users` iu ON it.`updated_by` = iu.`id`
				                    WHERE iu.`user_roles_id` = 5
				                    AND it.`status` = 'successful'
                                    AND iu.`id` NOT IN (69, 31, 16, 74, 70)
				                    AND iu.`username` NOT LIKE '%tester%'
				                    AND iu.`username` NOT LIKE '%trial%'
				                    AND DATE_FORMAT(it.`complete_time`, '%Y-%m-%d')
						                BETWEEN DATE_SUB(DATE(NOW()), INTERVAL DAYOFWEEK(NOW())+14 DAY)
										AND DATE_SUB(DATE(NOW()), INTERVAL DAYOFWEEK(NOW())+6 DAY)
				                    GROUP BY iu.`id`
				                ) as itempp
				            ) as total_allspeed
				            FROM `users` u
				            INNER JOIN `transactions` t ON u.`id` = t.`updated_by` 
				            WHERE u.`user_roles_id` = 5
				            AND t.`status` = 'successful'
                            AND u.`id` NOT IN (69, 31, 16, 74, 70)
		                    AND u.`username` NOT LIKE '%tester%'
		                    AND u.`username` NOT LIKE '%trial%'
				            AND DATE_FORMAT(t.`complete_time`, '%Y-%m-%d')
				                BETWEEN DATE_SUB(DATE(NOW()), INTERVAL DAYOFWEEK(NOW())+14 DAY)
								AND DATE_SUB(DATE(NOW()), INTERVAL DAYOFWEEK(NOW())+6 DAY)
				            GROUP BY u.`id`
				        ) as temp
				    ) as tmep_1
				)*100, 2) as total_score
				FROM
				(
				    SELECT
				    cs_id,
				    cs_name,
				    speed_min,
				    total_trans,
				    CASE
				        WHEN speed_min <= 1 THEN 5*total_trans
				        WHEN speed_min >= 1.1 AND speed_min <= 2 THEN 4*total_trans
				        WHEN speed_min >= 2.1 AND speed_min <= 3 THEN 3*total_trans
				        WHEN speed_min >= 3.1 AND speed_min <= 4 THEN 2*total_trans
				        WHEN speed_min >= 4.1 AND speed_min <= 5 THEN 1*total_trans
				        WHEN speed_min >= 5.1 AND speed_min <= 10 THEN 0.75*total_trans
				        WHEN speed_min > 10.1 THEN 0.25*total_trans
				    END/total_allspeed + total_trans/total_alltrans/2 AS SCORE
				    FROM
				    (
				        SELECT
				        u.`id` as cs_id,
				        u.`complete_name` as cs_name,
				        ROUND(AVG(TIMESTAMPDIFF(SECOND,t.`request_time`,t.`complete_time`) - TIME_TO_SEC(ifnull(t.`pending_time`, 0)))/60, 1) as speed_min,
				        COUNT(t.`id`) as total_trans,
				        (
				            SELECT
				            SUM(total)
				            FROM(
				                SELECT
				                COUNT(it.`id`) as total
				                FROM `transactions` it
				                INNER JOIN `users` iu ON it.`updated_by` = iu.`id`
				                WHERE iu.`user_roles_id` = 5
				                AND it.`status` = 'successful'
                                AND iu.`id` NOT IN (69, 31, 16, 74, 70)
			                    AND iu.`username` NOT LIKE '%tester%'
			                    AND iu.`username` NOT LIKE '%trial%'
				                AND DATE_FORMAT(it.`complete_time`, '%Y-%m-%d')
					                BETWEEN DATE_SUB(DATE(NOW()), INTERVAL DAYOFWEEK(NOW())+14 DAY)
									AND DATE_SUB(DATE(NOW()), INTERVAL DAYOFWEEK(NOW())+6 DAY)
				            ) as itemp
				        ) as total_alltrans,
				        (
				            SELECT
				            SUM(total_csspeed)
				            FROM(
				                SELECT 
				                ROUND(AVG(TIMESTAMPDIFF(SECOND,it.`request_time`,it.`complete_time`) - TIME_TO_SEC(ifnull(it.`pending_time`, 0)))/60, 1) as total_csspeed
				                FROM `transactions` it
				                INNER JOIN `users` iu ON it.`updated_by` = iu.`id`
				                WHERE iu.`user_roles_id` = 5
				                AND it.`status` = 'successful'
                                AND iu.`id` NOT IN (69, 31, 16, 74, 70)
			                    AND iu.`username` NOT LIKE '%tester%'
			                    AND iu.`username` NOT LIKE '%trial%'
				                AND DATE_FORMAT(it.`complete_time`, '%Y-%m-%d')
					                BETWEEN DATE_SUB(DATE(NOW()), INTERVAL DAYOFWEEK(NOW())+14 DAY)
									AND DATE_SUB(DATE(NOW()), INTERVAL DAYOFWEEK(NOW())+6 DAY)
				                GROUP BY iu.`id`
				            ) as itempp
				        ) as total_allspeed
				        FROM `users` u
				        INNER JOIN `transactions` t ON u.`id` = t.`updated_by` 
				        WHERE u.`user_roles_id` = 5
				        AND t.`status` = 'successful'
                        AND u.`id` NOT IN (69, 31, 16, 74, 70)
	                    AND u.`username` NOT LIKE '%tester%'
	                    AND u.`username` NOT LIKE '%trial%'
				        AND DATE_FORMAT(t.`complete_time`, '%Y-%m-%d')
			                BETWEEN DATE_SUB(DATE(NOW()), INTERVAL DAYOFWEEK(NOW())+14 DAY)
							AND DATE_SUB(DATE(NOW()), INTERVAL DAYOFWEEK(NOW())+6 DAY)
				        GROUP BY u.`id`
				    ) as temp
				) as ftemp
				GROUP BY cs_id
				) as ppr
				ORDER BY total_score ASC
				LIMIT 3";
		$query = $this->db->query($sql, $data);
		$result = $query->result();
		return $result;	       
	}
}
