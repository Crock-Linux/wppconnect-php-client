<?php

namespace Wppconnect;

class Wppconnect
{


    public function __construct(array $options = [])
    {

        $this->options = [
            /**
             * Configures a base URL for the client so that requests created using
             * a relative URL are combined with the base_url
             */
            'base_url' => $options['base_url'],

            /**
             * Secret Key
             * See: https://github.com/wppconnect-team/wppconnect-server#secret-key
             */
            'secret_key' => $options['secret_key'],

            /**
             * Your Session Name
             */
            'session' => $options['session'],
        ];
    }

    /**
     * Debug function
     * Like laravel dd
     *
     * @param array $array
     * @return string
     */
    public function debug(array $array): void
    {
        echo "<pre>";
        print_r($array);
        echo "</pre>";
        die;
    }

  /**
   * toArray function
   *
   * @param object $content
   * @return array
   */
    public function toArray(string $content): array
    {
        $content =  json_decode($content, true);
        return (is_array($content)) ?  $content : ['status' => 'Error', 'message' => $content];
    }

    /**
     * Return the server information such as headers, paths
     * and script locations.
     *
     * @param string $id
     * @return string
     */
    protected function getServerVar(string $id): string
    {
        return isset($_SERVER[$id]) ? $_SERVER[$id] : '';
    }

    /**
     *  Create a header
     *
     * @param string $str
     * @return string
     */
    protected function header(string $str): string
    {
        return $str;
    }

    /**
     * Define a head
     *
     * @return void
     */
    protected function head(): void
    {
        $this->header('Pragma: no-cache');
        if (strpos($this->getServerVar('HTTP_ACCEPT'), 'application/json') !== false) :
            $this->header('Content-type: application/json');
        else :
            $this->header('Content-type: text/plain');
        endif;
    }

    /**
     * Create a body
     *
     * @param string $str
     * @return string
     */
    protected function body(string $str): string
    {
        return json_encode($str);
    }

    /**
     * Create a response
     *
     * @param string $content
     * @param boolean $print
     * @return string
     */
    protected function response(string $content, bool $print = true): string
    {
        if ($print) :
            $this->head();
            $this->body($content);
        endif;
        return $content;
    }

    /**
     * cURL to make POST and GET requests
     *
     * @param string $method
     * @param string $function
     * @param array $data
     * @return string
     */
    protected function sendCurl(string $method, string $function, array $data): string
    {
        /**
         * Route
         */
        $function = strtolower(preg_replace("([A-Z])", "-$0", $function));

        /**
         * Api URL
         */
        if ($function == "start-all") :
            $api =  'api/' . $this->options['secret_key'];
        else :
            $api = ($function == "generate-token") ? 'api/' . $this->options['session'] . '/' .
                $this->options['secret_key'] : 'api/' . $this->options['session'];
        endif;

        /**
         * Header define
         */
        $header = ['Content-Type: application/json','Cache-control: no-cache'];
        if (isset($_SESSION['token'])) :
            array_push($header, 'Authorization: Bearer ' . $_SESSION['token']);
        endif;

        /**
         * Request via cURL
         */
        $ch = curl_init();
        if ($method == "post") :
            curl_setopt($ch, CURLOPT_URL, $this->options['base_url'] . '/' .  $api . '/' . $function);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        else :
            if ($function == "get-media-by-message") :
                curl_setopt($ch, CURLOPT_URL, $this->options['base_url'] . '/' .  $api . '/' . $function .
                '/' . $data['messageId']);
            else :
                curl_setopt($ch, CURLOPT_URL, $this->options['base_url'] . '/' .  $api . '/' . $function .
                '?' . http_build_query($data));
            endif;
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        endif;
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        #some servers need SSL VERIFY PEER option. if your case, please uncomment it.
        //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER , false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        $result = curl_exec($ch);
        if ($result === false) :
            echo 'Curl error: ' . curl_error($ch);
            die;
        endif;
        curl_close($ch);
        return $this->response($result);
    }

    /**
     * Generation Token
     *
     * @param array $data
     * @return string
     */
    public function generateToken(array $data): string
    {
        return $this->sendCurl('post', __FUNCTION__, $data);
    }

    /**
     * Start All Session
     *
     * @param array $data
     * @return string
     */
    public function startAll(array $data): string
    {
        return $this->sendCurl('post', __FUNCTION__, $data);
    }

    /**
     * Start Session
     *
     * @param array $data
     * @return string
     */
    public function startSession(array $data): string
    {
        return $this->sendCurl('post', __FUNCTION__, $data);
    }


    /**
     * Show All Session
     *
     * @param array $data
     * @return string
     */
    public function showAllSessions(array $data): string
    {
        return $this->sendCurl('get', __FUNCTION__, $data);
    }


    /**
     * Close Session
     *
     * @param array $data
     * @return string
     */
    public function closeSession(array $data): string
    {
        return $this->sendCurl('post', __FUNCTION__, $data);
    }

    /**
     * Check Connection Session
     *
     * @param array $data
     * @return string
     */
    public function checkConnectionSession(array $data): string
    {
        return $this->sendCurl('get', __FUNCTION__, $data);
    }

    /**
     * Get Media By Message
     *
     * @param array $data
     * @return string
     */
    public function getMediaByMessage(array $data): string
    {
        return $this->sendCurl('get', __FUNCTION__, $data);
    }

    /**
     * Send Message
     *
     * @param array $data
     * @return string
     */
    public function sendMessage(array $data): string
    {
        return $this->sendCurl('post', __FUNCTION__, $data);
    }

    /**
     * Send Image
     *
     * @param array $data
     * @return string
     */
    public function sendImage(array $data): string
    {
        return $this->sendCurl('post', __FUNCTION__, $data);
    }

    /**
     * Send File
     *
     * @param array $data
     * @return string
     */
    public function sendFile(array $data): string
    {
        return $this->sendCurl('post', __FUNCTION__, $data);
    }

    /**
     * Send Voice
     *
     * @param array $data
     * @return string
     */
    public function sendVoice(array $data): string
    {
        return $this->sendCurl('post', __FUNCTION__, $data);
    }

    /**
     * Send Status
     *
     * @param array $data
     * @return string
     */
    public function sendStatus(array $data): string
    {
        return $this->sendCurl('post', __FUNCTION__, $data);
    }

    /**
     * Send File Base64
     *
     * @param array $data
     * @return string
     */
    public function sendFileBase64(array $data): string
    {
        return $this->sendCurl('post', __FUNCTION__, $data);
    }

    /**
     * Send Link Preview
     *
     * @param array $data
     * @return string
     */
    public function sendLinkPreview(array $data): string
    {
        return $this->sendCurl('post', __FUNCTION__, $data);
    }

    /**
     * Send Location
     *
     * @param array $data
     * @return string
     */
    public function sendLocation(array $data): string
    {
        return $this->sendCurl('post', __FUNCTION__, $data);
    }

    /**
     * Create Group
     *
     * @param array $data
     * @return string
     */
    public function createGroup(array $data): string
    {
        return $this->sendCurl('post', __FUNCTION__, $data);
    }

    /**
     * Leave Group
     *
     * @param array $data
     * @return string
     */
    public function leaveGroup(array $data): string
    {
        return $this->sendCurl('post', __FUNCTION__, $data);
    }

    /**
     * Join Code
     *
     * @param array $data
     * @return string
     */
    public function joinCode(array $data): string
    {
        return $this->sendCurl('post', __FUNCTION__, $data);
    }

    /**
     * Group Members
     *
     * @param array $data
     * @return string
     */
    public function groupMembers(array $data): string
    {
        return $this->sendCurl('post', __FUNCTION__, $data);
    }

    /**
     * Add Participant Group
     *
     * @param array $data
     * @return string
     */
    public function addParticipantGroup(array $data): string
    {
        return $this->sendCurl('post', __FUNCTION__, $data);
    }

    /**
     * Add Participant Group
     *
     * @param array $data
     * @return string
     */
    public function removeParticipantGroup(array $data): string
    {
        return $this->sendCurl('post', __FUNCTION__, $data);
    }

    /**
     * Promote Participant Group
     *
     * @param array $data
     * @return string
     */
    public function promoteParticipantGroup(array $data): string
    {
        return $this->sendCurl('post', __FUNCTION__, $data);
    }

    /**
     * Demote Participant Group
     *
     * @param array $data
     * @return string
     */
    public function demoteParticipantGroup(array $data): string
    {
        return $this->sendCurl('post', __FUNCTION__, $data);
    }

    /**
     * Group Admins
     *
     * @param array $data
     * @return string
     */
    public function groupAdmins(array $data): string
    {
        return $this->sendCurl('post', __FUNCTION__, $data);
    }

    /**
     * Group Invite Link
     *
     * @param array $data
     * @return string
     */
    public function groupInviteLink(array $data): string
    {
        return $this->sendCurl('post', __FUNCTION__, $data);
    }

    /**
     * Change Privacy Group
     *
     * @param array $data
     * @return string
     */
    public function changePrivacyGroup(array $data): string
    {
        return $this->sendCurl('post', __FUNCTION__, $data);
    }


    /**
     * Change Username
     *
     * @param array $data
     * @return string
     */
    public function changeUsername(array $data): string
    {
        return $this->sendCurl('post', __FUNCTION__, $data);
    }

    /**
     * Show All Contacts
     *
     * @param array $data
     * @return string
     */
    public function showAllContacts(array $data): string
    {
        return $this->sendCurl('get', __FUNCTION__, $data);
    }

    /**
     * Show All Chats
     *
     * @param array $data
     * @return string
     */
    public function showAllChats(array $data): string
    {
        return $this->sendCurl('get', __FUNCTION__, $data);
    }

    /**
     * Show All Groups
     *
     * @param array $data
     * @return string
     */
    public function showAllGroups(array $data): string
    {
        return $this->sendCurl('post', __FUNCTION__, $data);
    }

    /**
     * Show All Blocklist
     *
     * @param array $data
     * @return string
     */
    public function showAllBlocklist(array $data): string
    {
        return $this->sendCurl('post', __FUNCTION__, $data);
    }


    /**
     * Get Chat By Id
     *
     * @param array $data
     * @return string
     */
    public function getChatById(array $data): string
    {
        return $this->sendCurl('post', __FUNCTION__, $data);
    }

    /**
     * Get Battery Level
     *
     * @param array $data
     * @return string
     */
    public function getBatteryLevel(array $data): string
    {
        return $this->sendCurl('post', __FUNCTION__, $data);
    }

    /**
     * Get Delete Chat
     *
     * @param array $data
     * @return string
     */
    public function deleteChat(array $data): string
    {
        return $this->sendCurl('post', __FUNCTION__, $data);
    }


    /**
     * Get Clear Chat
     *
     * @param array $data
     * @return string
     */
    public function clearChat(array $data): string
    {
        return $this->sendCurl('post', __FUNCTION__, $data);
    }

    /**
     * Archive Chat
     *
     * @param array $data
     * @return string
     */
    public function archiveChat(array $data): string
    {
        return $this->sendCurl('post', __FUNCTION__, $data);
    }

    /**
     * Delete Message
     *
     * @param array $data
     * @return string
     */
    public function deleteMessage(array $data): string
    {
        return $this->sendCurl('post', __FUNCTION__, $data);
    }

    /**
     * Mark Unseen Contact
     *
     * @param array $data
     * @return string
     */
    public function markUnseenContact(array $data): string
    {
        return $this->sendCurl('post', __FUNCTION__, $data);
    }

    /**
     * Block Contact
     *
     * @param array $data
     * @return string
     */
    public function blockContact(array $data): string
    {
        return $this->sendCurl('post', __FUNCTION__, $data);
    }


    /**
     * Unblock Contact
     *
     * @param array $data
     * @return string
     */
    public function unblockContact(array $data): string
    {
        return $this->sendCurl('post', __FUNCTION__, $data);
    }

    /**
     * Get Host Device
     *
     * @param array $data
     * @return string
     */
    public function getHostDevice(array $data): string
    {
        return $this->sendCurl('post', __FUNCTION__, $data);
    }

    /**
     * Forward Messages
     *
     * @param array $data
     * @return string
     */
    public function forwardMessages(array $data): string
    {
        return $this->sendCurl('post', __FUNCTION__, $data);
    }

    /**
     * Pin Chat
     *
     * @param array $data
     * @return string
     */
    public function pinChat(array $data): string
    {
        return $this->sendCurl('post', __FUNCTION__, $data);
    }

    /**
     * Download Media
     *
     * @param array $data
     * @return string
     */
    public function downloadMedia(array $data): string
    {
        return $this->sendCurl('post', __FUNCTION__, $data);
    }
}