<?php
 
function extract_domain($domain) {
    if(preg_match("/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i", $domain, $matches)) {
        return $matches['domain'];
    } else {
        return $domain;
    }
}

$domain = extract_domain($_SERVER['SERVER_NAME']);
$mailServeur='mail.'.$domain;
 
if (preg_match('/^\/mail\/config-v1\.1\.xml/', $_SERVER['REQUEST_URI'])) {
    header('Content-Type: text/xml');
    header('Content-Type: application/xml');
    ?>
<clientConfig version="1.1">
    <emailProvider id="<?= $domain ?>">
      <domain><?= $domain ?></domain>
      <displayName><?= $domain ?></displayName>
      <displayShortName><?= $domain ?></displayShortName>
      <incomingServer type="imap">
         <hostname><?= $mailServeur ?></hostname>
         <port>993</port>
         <socketType>SSL</socketType>
         <username>%EMAILADDRESS%</username>
         <authentication>password-cleartext</authentication>
      </incomingServer>
      <incomingServer type="pop3">
         <hostname><?= $mailServeur ?></hostname>
         <port>995</port>
         <socketType>SSL</socketType>
         <username>%EMAILADDRESS%</username>
         <authentication>password-cleartext</authentication>
      </incomingServer>
      <outgoingServer type="smtp">
         <hostname><?= $mailServeur ?></hostname>
         <port>465</port>
         <socketType>SSL</socketType>
         <username>%EMAILADDRESS%</username>
         <authentication>password-cleartext</authentication>
      </outgoingServer>
      <documentation url="https://webmail.<?= $domain ?>">
          <descr lang="fr">Connexion Webmail</descr>
          <descr lang="en">Webmail connexion</descr>
          <descr lang="it">Connessione Webmail</descr>
      </documentation>
    </emailProvider>
</clientConfig>
    <?php
} else {
    // Outlook
    //get raw POST data so we can extract the email address
    $data = file_get_contents("php://input");
    preg_match("/\<EMailAddress\>(.*?)\<\/EMailAddress\>/", $data, $matches);
 
    //set Content-Type
    header('Content-Type: text/xml');
    header('Content-Type: application/xml');
    echo '<?xml version="1.0" encoding="utf-8" ?>'; 
    ?>
<Autodiscover xmlns="http://schemas.microsoft.com/exchange/autodiscover/responseschema/2006">
   <Response xmlns="http://schemas.microsoft.com/exchange/autodiscover/outlook/responseschema/2006a">
       <Account>
           <AccountType>email</AccountType>
           <Action>settings</Action>
           <Protocol>
               <Type>IMAP</Type>
               <Server><?= $mailServeur ?></Server>
               <Port>993</Port>
               <DomainRequired>on</DomainRequired>
               <LoginName><?php echo $matches[1]; ?></LoginName>
               <SPA>off</SPA>
               <SSL>on</SSL>
               <AuthRequired>on</AuthRequired>
           </Protocol>
           <Protocol>
               <Type>POP3</Type>
               <Server><?= $mailServeur ?></Server>
               <Port>995</Port>
               <DomainRequired>on</DomainRequired>
               <LoginName><?php echo $matches[1]; ?></LoginName>
               <SPA>off</SPA>
               <SSL>on</SSL>
               <AuthRequired>on</AuthRequired>
           </Protocol>
           <Protocol>
               <Type>SMTP</Type>
               <Server><?= $mailServeur ?></Server>
               <Port>465</Port>
               <DomainRequired>on</DomainRequired>
               <LoginName><?php echo $matches[1]; ?></LoginName>
               <SPA>off</SPA>
               <Encryption>SSL</Encryption>
               <AuthRequired>on</AuthRequired>
               <UsePOPAuth>on</UsePOPAuth>
               <SMTPLast>on</SMTPLast>
           </Protocol>
       </Account>
   </Response>
</Autodiscover>
    <?php
}
 
?>
