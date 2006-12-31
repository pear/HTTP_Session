<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Database container for session data
 *
 * Memcache database container
 *
 * PHP version 4
 *
 * LICENSE: This source file is subject to version 3.0 of the PHP license
 * that is available through the world-wide-web at the following URI:
 * http://www.php.net/license/3_0.txt.  If you did not receive a copy of
 * the PHP License and are unable to obtain it through the web, please
 * send a note to license@php.net so we can mail you a copy immediately.
 *
 * @category   HTTP
 * @package    HTTP_Session
 * @author     Chad Wagner <chad.wagner@gmail.com>
 * @author     Torsten Roehr <torsten.roehr@gmx.de>
 * @copyright  1997-2007 The PHP Group
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version    CVS: $Id$
 * @link       http://pear.php.net/package/HTTP_Session
 * @since      File available since Release 0.5.6
 */

require_once 'HTTP/Session/Container.php';

/**
 * Database container for session data
 *
 * @category   HTTP
 * @package    HTTP_Session
 * @author     Chad Wagner <chad.wagner@gmail.com>
 * @author     Torsten Roehr <torsten.roehr@gmx.de>
 * @copyright  1997-2007 The PHP Group
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version    Release: @package_version@
 * @link       http://pear.php.net/package/HTTP_Session
 * @since      Class available since Release 0.5.6
 */
class HTTP_Session_Container_Memcache extends HTTP_Session_Container
{
    /**
     * Memcache connection object
     *
     * @var     object  Memcache
     * @access  private
     */
    var $mc;

    /**
     * Session data cache id
     *
     * @var     mixed
     * @access  private
     */
    var $crc = false;

    /**
     * Constructor method
     *
     * $options is an array with the options.<br>
     * The options are:
     * <ul>
     * <li>'memcache' - Memcache object
     * <li>'prefix' - Key prefix, default is 'sessiondata:'</li>
     * </ul>
     *
     * @access  public
     * @param   array   $options    The options
     * @return  void
     */
    function HTTP_Session_Container_Memcache($options)
    {
        $this->_setDefaults();

        if (is_array($options)) {
            $this->_parseOptions($options);
        }
    }

    /**
     * Connect to database by using the given DSN string
     *
     * @access  private
     * @param   string  DSN string
     * @return  mixed   Object on error, otherwise bool
     */
    function _connect($mc)
    {
        if (is_object($mc) && is_a($mc, 'Memcache')) {
            $this->mc = $mc;

        } else {

            return new PEAR_Error('The given memcache object was not valid in file ' . __FILE__ . ' at line ' . __LINE__,
                                  41,
                                  PEAR_ERROR_RETURN,
                                  null,
                                  null
                                 );
        }

        return true;
    }

    /**
     * Set some default options
     *
     * @access  private
     */
    function _setDefaults()
    {
        $this->options['prefix']   = 'sessiondata:';
        $this->options['memcache'] = null;
    }

    /**
     * Establish connection to a database
     *
     * @access  public
     * @param   string  $save_path      Save path
     * @param   string  $session_name   Session name
     * @return  mixed   Object on error, otherwise bool
     */
    function open($save_path, $session_name)
    {
        return $this->_connect($this->options['memcache']);
    }

    /**
     * Free resources
     *
     * @access  public
     * @return  bool
     */
    function close()
    {
        return true;
    }

    /**
     * Read session data
     *
     * @access  public
     * @param   string  $id     Session id
     * @return  mixed
     */
    function read($id)
    {
        $result    = $this->mc->get($this->options['prefix'] . $id);
        $this->crc = strlen($result) . crc32($result);
        return $result;
    }

    /**
     * Write session data
     *
     * @access  public
     * @param   string  $id     Session id
     * @param   mixed   $data   Session data
     * @return  bool
     */
    function write($id, $data)
    {
        if ($this->crc !== strlen($data) . crc32($data)) {

            $this->mc->set($this->options['prefix'] . $id,
                           $data,
                           MEMCACHE_COMPRESSED,
                           time() + ini_get('session.gc_maxlifetime')
                          );
        }

        return true;
    }

   /**
     * Destroy session data
     *
     * @access  public
     * @param   string  $id     Session id
     * @return  bool
     */
    function destroy($id)
    {
        $this->mc->delete($this->options['prefix'] . $id);
        return true;
    }

    /**
     * Garbage collection
     *
     * @access  public
     * @param   int     $maxlifetime    Maximum lifetime
     * @return  bool
     */
    function gc($maxlifetime)
    {
        return true;
    }
}
?>