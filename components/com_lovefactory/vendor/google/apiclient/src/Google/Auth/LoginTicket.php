<?php

/**
-------------------------------------------------------------------------
lovefactory - Love Factory 4.4.7
-------------------------------------------------------------------------
 * @author thePHPfactory
 * @copyright Copyright (C) 2011 SKEPSIS Consult SRL. All Rights Reserved.
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * Websites: http://www.thePHPfactory.com
 * Technical Support: Forum - http://www.thePHPfactory.com/forum/
-------------------------------------------------------------------------
*/

defined('_JEXEC') or die;

/*
 * Copyright 2011 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

require_once "Google/Auth/Exception.php";

/**
 * Class to hold information about an authenticated login.
 *
 * @author Brian Eaton <beaton@google.com>
 */
class Google_Auth_LoginTicket
{
    const USER_ATTR = "sub";

    // Information from id token envelope.
    private $envelope;

    // Information from id token payload.
    private $payload;

    /**
     * Creates a user based on the supplied token.
     *
     * @param string $envelope Header from a verified authentication token.
     * @param string $payload Information from a verified authentication token.
     */
    public function __construct($envelope, $payload)
    {
        $this->envelope = $envelope;
        $this->payload = $payload;
    }

    /**
     * Returns the numeric identifier for the user.
     * @throws Google_Auth_Exception
     * @return
     */
    public function getUserId()
    {
        if (array_key_exists(self::USER_ATTR, $this->payload)) {
            return $this->payload[self::USER_ATTR];
        }
        throw new Google_Auth_Exception("No user_id in token");
    }

    /**
     * Returns attributes from the login ticket.  This can contain
     * various information about the user session.
     * @return array
     */
    public function getAttributes()
    {
        return array("envelope" => $this->envelope, "payload" => $this->payload);
    }
}
