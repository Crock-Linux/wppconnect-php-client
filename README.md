# WPPConnect Team
## _Wppconnect PHP Client_

Um simples cliente PHP que proporciona acesso fácil aos endpoints do WPPConnect Server.

## Nossos Canais Online

[![Discord](https://img.shields.io/discord/844351092758413353?color=blueviolet&label=Discord&logo=discord&style=flat)](https://discord.gg/JU5JGGKGNG)
[![Telegram Group](https://img.shields.io/badge/Telegram-Group-32AFED?logo=telegram)](https://t.me/wppconnect)
[![WhatsApp Group](https://img.shields.io/badge/WhatsApp-Group-25D366?logo=whatsapp)](https://chat.whatsapp.com/C1ChjyShl5cA7KvmtecF3L)
[![YouTube](https://img.shields.io/youtube/channel/subscribers/UCD7J9LG08PmGQrF5IS7Yv9A?label=YouTube)](https://www.youtube.com/c/wppconnect)

## Requisitos

* PHP 7.0 ou superior.

## Configuração

Array associativo que será aplicado a todas as solicitações criadas pelo cliente.

Exemplo: 

``` php
$this->options = [
    /**
      * URL do WPPConnect Server
     */
    'base_url' => 'http://localhost:8081',
    
    /**
     * Secret Key
     * Veja: https://github.com/wppconnect-team/wppconnect-server#secret-key
     */
    'secret_key' => 'MYKeYPHP',

    /**
     * Nome da Session
     */
    'session' => 'mySession'
];
```

## Uso

``` php
namespace Wppconnect;

# Use require or autoload
require('wppconnect.php');

$wppconnect = new Wppconnect([
    'base_url' => 'http://localhost:8081',
    'secret_key' => 'MYKeYPHP',
    'session' => 'mySession',
]);
 ```
 
 ``` php
# Function: Generated Token
# /api/:session/generate-token
$response = $wppconnect->generateToken([
    'session' => $wppconnect->options['session'],
    'secret_key' => $wppconnect->options['secret_key']
]);
$response = $wppconnect->toArray($response);
#debug
$wppconnect->debug($response);
 ```
 ``` php
 # Function: Start Session
 # /api/:session/start-session
$response = $wppconnect->startSession([
    'session' => $wppconnect->options['session'],
    'webhook' => null,
    'waitQrCode' => true
    ]);
$response = $wppconnect->toArray($response);
#debug
$wppconnect->debug($response);
 ```
 ``` php
# Function: Check Connection Session
# /api/:session/check-connection-session
$response = $wppconnect->checkConnectionSession([
    'session' => $wppconnect->options['session'],
]);
$response = $wppconnect->toArray($response);
#debug
$wppconnect->debug($response);

 ```
 ``` php
# Function: Send Message
# /api/:session/send-message    
$response = $wppconnect->sendMessage([
    'session' => $wppconnect->options['session'],
    'phone' => '5500000000000',
    'message' => 'Opa, funciona mesmo!',
    'isGroup' => false
]);
$response = $wppconnect->toArray($response);
#debug
$wppconnect->debug($response);

 ```
 ``` php
# Function: Send File Base64
# /api/:session/send-file-base64 
$response = $wppconnect->sendFileBase64([
    'session' => $wppconnect->options['session'],
    'phone' => '5500000000000',
    'filename' => 'Xpto',
    'base64' => $wppconnect->fileToBase64('xpto.jpg'),
    'isGroup' => false
]);
$response = $wppconnect->toArray($response);
#debug
$wppconnect->debug($response);
 ```
 
``` php
# Function: Send Link Preview
# /api/:session/send-link-preview
$response = $wppconnect->sendLinkPreview([
    'session' => $wppconnect->options['session'],
    'phone' => '5500000000000',
    'url' => 'https://github.com/wppconnect-team',
    'caption' => 'WppConnectTeam',
    'isGroup' => false
]);
$response = $wppconnect->toArray($response);
#debug
$wppconnect->debug($response);
 ```
 ``` php
# Function: Send Location
# /api/:session/send-location 
$response = $wppconnect->sendLocation([
    'session' => $wppconnect->options['session'],
    'phone' => '5500000000000',
    'lat' => '-23.5489',
    'lng' => '-46.6388',
    'title' => 'Cidade de São Paulo'
    'isGroup' => false
]);
$response = $wppconnect->toArray($response);
#debug
$wppconnect->debug($response);
 ```

## Funções/Métodos Suportados (até este momento) 

Este cliente PHP ainda está em desenvolvimento. 
Veja [aqui](https://github.com/wppconnect-team/wppconnect-server/blob/main/src/routes/index.js) todos os endpoints do WPPConnect Server. 

### Token
- generateToken([:session,:secret_key]) 

### Session
- startAll([:secret_key])
- showAllSessions([:session]);
- startSession([:session,:webhook,:waitQrCode]);
- closeSession([:session]);
- logoutSession([:session]);
- checkConnectionSession([:session]);
- statusSession([:session]);
- qrcodeSession([:session]);

### Mensagem
- sendMessage([:session,:phone,:message,:isGroup]);
- sendReply([:session,:phone,:message,:messageId,:isGroup]);
- sendFileBase64([:session,:phone,:filename:base64:isGroup]);
- sendStatus([:session,:message,:isGroup]);
- sendLinkPreview([:session,:phone,:url,:caption,:isGroup]);
- sendLocation([:session,:phone,:lat,:lng,:title,:isGroup]);
- sendMentioned([:session,:phone,:message,:mentioned,:isGroup]);

### Grupo
- createGroup([:session,:participants[:phone,:phone,...],:name]);
- leaveGroup([:session,:groupId]);
- joinCode([:session,:inviteCode]);
- groupMembers([:session,:groupId]);
- addParticipantGroup([:session,:groupId,:phone]);
- removeParticipantGroup([:session,:groupId,:phone,]);
- promoteParticipantGroup([:session,:groupId,:phone]);
- demoteParticipantGroup([:session,:groupId,:phone]);
- groupAdmins([:session,:groupId]);
- groupInviteLink([:session,:groupId]);
- groupRevokeLink([:session,:groupId]);
- allGroups([:session]);
- groupInfoFromInviteLink([:session,:inviteCode]);
- groupMembersIds([:session,:groupId]);
- groupDescription([:session,:groupId,:description]);
- groupProperty([:session,:groupId,:property,:value]);
- groupSubject([:session,:groupId,:title]);
- messagesAdminsOnly([:session,:groupId,:value]);

### Chat
- archiveChat([:session,:phone,:isGroup]);
- clearChat([:session,:phone,:isGroup]);
- deleteChat([:session,:phone]);
- deleteMessage([:session,:phone,:messageId]);
- forwardMessages([:session,:phone,:messageId]);
- allChats([:session]);
- allChatsWithMessages([:session]);
- allMessagesInChat([:session,:phone]);
- allNewMessages([:session,:phone]);
- unreadMessages([:session]);
- allUnreadMessages([:session]);
- chatById([:session,:phone]);
- chatIsOnline([:session,:phone]);
- lastSeen([:session,:phone]);
- listMutes([:session,:type]);
- loadMessagesInChat([:session,:phone]);
- markUnseen([:session,:phone]);
- pinChat([:session,:phone,:state,:isGroup]);
- contactVcard([:session,:phone,:contactsId]);
- sendMute([:session,:phone,:time,:type]);
- sendSeen([:session,:phone]);
- chatState([:session,:phone,:chatstate]);
- typing([:session,:phone,:value,:isGroup]);
- starMessage([:session,:messageId,:star]);
- getMediaByMessage([:session,:messageId]);

### Contatos
- checkNumberStatus([:session,:phone]);
- allContacts([:session]);
- contact([:session,:phone]);
- profile([:session,:phone,]);
- profilePic([:session,:phone]);
- profileStatus([:session,:phone]);
- blockContact([:session,:phone]);
- unblockContact([:session,:phone]);
- blocklist([:session]);
- setProfileStatus([:session,:status]);
- changeUsername([:session,:name]);

### Device
- getBatteryLevel([:session]);
- hostDevice([:session]);

### Outros
- allBroadcastList([:session]);
- subscribePresence([:session,:isGroup,:all]);
- killServiceWorkier([:session]);
- restartService([:session]);

## Webhook

Exemplo de [classe](https://github.com/wppconnect-team/wppconnect-php-client/blob/main/util/webhook.php) para registrar/obter a solicitação/respostas do webhook WPPConnect.

### Uso

``` php
namespace Wppconnect;

# Use require or autoload
require('util/webhook.php');

$webhook = new Webhook();
$requestData = $webhook->getRequest();
```

## Postman

Acesse o [Postman Collection do WPPConnect](https://documenter.getpostman.com/view/9139457/TzshF4jQ) com todos os endpoints.
