<?php

/**
 * @copyright Copyright (c) Metaways Infosystems GmbH, 2011
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 */

return array(
	'item' => array(
		'aggregate' => '
			SELECT "key", COUNT("id") AS "count"
			FROM (
				SELECT DISTINCT :key AS "key", mcusli."id" AS "id"
				FROM "mshop_customer_list" AS mcusli
				:joins
				WHERE :cond
				/*-orderby*/ ORDER BY :order /*orderby-*/
				LIMIT :size OFFSET :start
			) AS list
			GROUP BY "key"
		',
		'delete' => '
			DELETE FROM "mshop_customer_list"
			WHERE :cond AND siteid = ?
		',
		'getposmax' => '
			SELECT MAX( "pos" ) AS pos
			FROM "mshop_customer_list"
			WHERE "siteid" = ? AND "parentid" = ? AND "typeid" = ?
				AND "domain" = ?
		',
		'insert' => '
			INSERT INTO "mshop_customer_list" (
				"parentid", "siteid", "typeid", "domain", "refid", "start",
				"end", "config", "pos", "status", "mtime", "editor", "ctime"
			) VALUES (
				?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
			)
		',
		'update' => '
			UPDATE "mshop_customer_list"
			SET "parentid"=?, "siteid" = ?, "typeid" = ?, "domain" = ?,
				"refid" = ?, "start" = ?, "end" = ?, "config" = ?, "pos" = ?,
				"status" = ?, "mtime" = ?, "editor" = ?
			WHERE "id" = ?
		',
		'updatepos' => '
			UPDATE "mshop_customer_list"
				SET "pos" = ?, "mtime" = ?, "editor" = ?
			WHERE "id" = ?
		',
		'move' => '
			UPDATE "mshop_customer_list"
				SET "pos" = "pos" + ?, "mtime" = ?, "editor" = ?
			WHERE "siteid" = ? AND "parentid" = ? AND "typeid" = ?
				AND "domain" = ? AND "pos" >= ?
		',
		'search' => '
			SELECT DISTINCT mcusli."id", mcusli."parentid", mcusli."siteid",
				mcusli."typeid", mcusli."domain", mcusli."refid",
				mcusli."start", mcusli."end", mcusli."config", mcusli."pos",
				mcusli."status", mcusli."mtime", mcusli."editor",
				mcusli."ctime"
			FROM "mshop_customer_list" AS mcusli
			:joins
			WHERE :cond
			/*-orderby*/ ORDER BY :order /*orderby-*/
			LIMIT :size OFFSET :start
		',
		'count' => '
			SELECT COUNT(*) AS "count"
			FROM (
				SELECT DISTINCT mcusli."id"
				FROM "mshop_customer_list" AS mcusli
				:joins
				WHERE :cond
				LIMIT 10000 OFFSET 0
			) AS list
		',
	),
);
