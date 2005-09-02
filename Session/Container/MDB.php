<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Database container for session data
 *
 * PEAR::MDB database container
 *
 * PHP versions 4 and 5
 *
 * LICENSE: This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston,
 * MA  02111-1307  USA
 *
 * @category   HTTP
 * @package    HTTP_Session
 * @author     David Costa <gurugeek@php.net>
 * @author     Michael Metz <pear.metz@speedpartner.de>
 * @author     Stefan Neufeind <pear.neufeind@speedpartner.de>
 * @author     Torsten Roehr <torsten.roehr@gmx.de>
 * @copyright  1997-2005 The PHP Group
 * @license    http://www.gnu.org/licenses/lgpl.txt
 * @version    CVS: $Id$
 * @link       http://pear.php.net/package/HTTP_Session
 * @since      File available since Release 0.5.0
 */

require_once 'HTTP/Session/Container.php';
require_once 'MDB.php';

/**
 * Database container for session data
 *
 * Create the following table to store session data
 * <code>
 * CREATE TABLE `sessiondata` (
 *     `id` CHAR(32) NOT NULL,
 *     `expiry` INT UNSIGNED NOT NULL DEFAULT 0,
 *     `data` TEXT NOT NULL,
 *     PRIMARY KEY (`id`)
 * );
 * </code>
 *
 * @category   HTTP
 * @package    HTTP_Session
 * @author     David Costa <gurugeek@php.net>
 * @author     Michael Metz <pear.metz@speedpartner.de>
 * @author     Stefan Neufeind <pear.neufeind@speedpartner.de>
 * @author     Torsten Roehr <torsten.roehr@gmx.de>
 * @copyright  1997-2005 The PHP Group
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version    Release: @package_version@
 * @link       http://pear.php.net/package/HTTP_Session
 * @since      Class available since Release 0.4.0
 */
class HTTP_Session_Container_MDB extends HTTP_Session_Container
{

    /**
     * MDB connection object
     *
     * @var object MDB
     * @access private
     */
    var $db = null;

    /**
     * Session data cache id
     *
     * @var mixed
     * @access private
     */
    var $crc = false;

    /**
     * Constructor method
     *
     * $options is an array with the options.<br>
     * The options are:
     * <ul>
     * <li>'dsn' - The DSN string</li>
     * <li>'table' - Table with session data, default is 'sessiondata'</li>
     * <li>'autooptimize' - Boolean, 'true' to optimize
     * the table on garbage collection, default is 'false'.</li>
     * </ul>
     *
     * @access public
     * @param  array  $options The options
     * @return void
     */
    function HTTP_Session_Container_MDB($options)
    {
        $this->_setDefaults();
        if (is_array($options)) {
            $this->_parseOptions($options);
        } else {
            $this->options['dsn'] = $options;
        }
    }

    /**
     * Connect to database by using the given DSN string
     *
     * @access private
     * @param  string DSN string
     * @return mixed  Object on error, otherwise bool
     */
    function _connect($dsn)
    {
        if (is_string($dsn) || is_array($dsn)) {
            $this->db = MDB::connect($dsn);
        } else if (is_object($dsn) && is_a($dsn, 'mdb_common')) {
            $this->db = $dsn;
        } else if (is_object($dsn) && MDB::isError($dsn)) {
            return new MDB_Error($dsn->code, PEAR_ERROR_DIE);
        } else {
            return new PEAR_Error("The given dsn was not valid in file " . __FILE__ . " at line " . __LINE__,
                                  41,
                                  PEAR_ERROR_RETURN,
                                  null,
                                  null
                                  );

        }

        if (MDB::isError($this->db)) {
            return new MDB_Error($this->db->code, PEAR_ERROR_DIE);
        }

        return true;
    }

    /**
     * Set some default options
     *
     * @access private
     */
    function _setDefaults()
    {
        $this->options['dsn']           = null;
        $this->options['table']         = 'sessiondata';
        $this->options['autooptimize']  = false;
    }

    /**
     * Establish connection to a database
     *
     */
    function open($save_path, $session_name)
    {
        if (MDB::isError($this->_connect($this->options['dsn']))) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Free resources
     *
     */
    function close()
    {
        return true;
    }

    /**
     * Read session data
     *
     */
    function read($id)
    {
        $query = sprintf("SELECT data FROM %s WHERE id = %s AND expiry >= %d",
                         $this->options['table'],
                         $this->db->getTextValue(md5($id)),
                         time()
                         );
        $result = $this->db->getOne($query);
        if (MDB::isError($result)) {
            new MDB_Error($result->code, PEAR_ERROR_DIE);
            return false;
        }
        $this->crc = strlen($result) . crc32($result);
        return $result;
    }

    /**
     * Write session data
     *
     */
    function write($id, $data)
    {
        if ((false !== $this->crc) && ($this->crc === strlen($data) . crc32($data))) {
            // $_SESSION hasn't been touched, no need to update the blob column
            $query = sprintf("UPDATE %s SET expiry = %d WHERE id = %s",
                             $this->options['table'],
                             time() + ini_get('session.gc_maxlifetime'),
                             $this->db->getTextValue(md5($id))
                             );
        } else {
            // Check if table row already exists
            $query = sprintf("SELECT COUNT(id) FROM %s WHERE id = %s",
                             $this->options['table'],
                             $this->db->getTextValue(md5($id))
                             );
            $result = $this->db->getOne($query);
            if (MDB::isError($result)) {
                new MDB_Error($result->code, PEAR_ERROR_DIE);
                return false;
            }
            if (0 == intval($result)) {
                // Insert new row into table
                $query = sprintf("INSERT INTO %s (id, expiry, data) VALUES (%s, %d, %s)",
                                 $this->options['table'],
                                 $this->db->getTextValue(md5($id)),
                                 time() + ini_get('session.gc_maxlifetime'),
                                 $this->db->getTextValue($data)
                                 );
            } else {
                // Update existing row
                $query = sprintf("UPDATE %s SET expiry = %d, data = %s WHERE id = %s",
                                 $this->options['table'],
                                 time() + ini_get('session.gc_maxlifetime'),
                                 $this->db->getTextValue($data),
                                 $this->db->getTextValue(md5($id))
                                 );
            }
        }
        $result = $this->db->query($query);
        if (MDB::isError($result)) {
            new MDB_Error($result->code, PEAR_ERROR_DIE);
            return false;
        }

        return true;
    }

    /**
     * Destroy session data
     *
     */
    function destroy($id)
    {
        $query = sprintf("DELETE FROM %s WHERE id = %s",
                         $this->options['table'],
                         $this->db->getTextValue(md5($id))
                         );
        $result = $this->db->query($query);
        if (MDB::isError($result)) {
            new MDB_Error($result->code, PEAR_ERROR_DIE);
            return false;
        }

        return true;
    }

    /**
     * Garbage collection
     *
     */
    function gc($maxlifetime)
    {
        $query = sprintf("DELETE FROM %s WHERE expiry < %d",
                         $this->options['table'],
                         time()
                         );
        $result = $this->db->query($query);
        if (MDB::isError($result)) {
            new MDB_Error($result->code, PEAR_ERROR_DIE);
            return false;
        }
        if ($this->options['autooptimize']) {
            switch($this->db->phptype) {
                case 'mysql':
                    $query = sprintf("OPTIMIZE TABLE %s", $this->options['table']);
                    break;
                case 'pgsql':
                    $query = sprintf("VACUUM %s", $this->options['table']);
                    break;
                default:
                    $query = null;
                    break;
            }
            if (isset($query)) {
                $result = $this->db->query($query);
                if (MDB::isError($result)) {
                    new MDB_Error($result->code, PEAR_ERROR_DIE);
                    return false;
                }
            }
        }

        return true;
    }

}

?>