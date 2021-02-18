<?php
/*
 * @package AJAX_Chat
 * @author Sebastian Tschan
 * @author Eric [June 7,2008]
 * @copyright (c) Sebastian Tschan
 * @license Modified MIT License
 * @link https://blueimp.net/ajax/
 */

$lang = [];
$lang['title'] = 'AJAX Chat';
$lang['userName'] = 'Användarnamn';
$lang['password'] = 'Lösenord';
$lang['login'] = 'Logga In';
$lang['logout'] = 'Logga Ut';
$lang['channel'] = 'Kanal';
$lang['style'] = 'Stil';
$lang['language'] = 'Språk';
$lang['inputLineBreak'] = 'Håll ner SHIFT+ENTER för att göra ett radbryt';
$lang['messageSubmit'] = 'Skicka';
$lang['registeredUsers'] = 'Registerade Användare';
$lang['onlineUsers'] = 'Användare OnLine';
$lang['toggleAutoScroll'] = 'Autoscroll av/på';
$lang['toggleAudio'] = 'Ljud av/på';
$lang['toggleHelp'] = 'Visa/göm hjälp';
$lang['toggleSettings'] = 'Visa/göm inställningar';
$lang['toggleOnlineList'] = 'Visa/göm OnLine-listan';
$lang['bbCodeLabelBold'] = 'b';
$lang['bbCodeLabelItalic'] = 'i';
$lang['bbCodeLabelUnderline'] = 'u';
$lang['bbCodeLabelQuote'] = 'Citera';
$lang['bbCodeLabelCode'] = 'Kod';
$lang['bbCodeLabelURL'] = 'URL';
$lang['bbCodeLabelImg'] = 'Image';
$lang['bbCodeLabelColor'] = 'Textfärg';
$lang['bbCodeLabelEmoticon'] = 'Smileys';
$lang['bbCodeTitleBold'] = 'Fet text: [b]text[/b]';
$lang['bbCodeTitleItalic'] = 'Kursiv text: [i]text[/i]';
$lang['bbCodeTitleUnderline'] = 'Understruken text: [u]text[/u]';
$lang['bbCodeTitleQuote'] = 'Citera text: [quote]text[/quote] or [quote=author]text[/quote]';
$lang['bbCodeTitleCode'] = 'Visa kod: [code]code[/code]';
$lang['bbCodeTitleURL'] = 'Lägg till URL: [url]http://www.example.org/[/url] or [url=http://www.example.org/]text[/url]';
$lang['bbCodeTitleImg'] = 'Infoga bild: [img]http://example.org/image.jpg[/img]';
$lang['bbCodeTitleColor'] = 'Textfärg: [color=red]text[/color]';
$lang['bbCodeTitleEmoticon'] = 'Smileys list';
$lang['help'] = 'Hjälp';
$lang['helpItemDescJoin'] = 'Anslut till kanal:';
$lang['helpItemCodeJoin'] = '/join Kanalens namn';
$lang['helpItemDescJoinCreate'] = 'Skapa privat rum (Endast registrerade användare):';
$lang['helpItemCodeJoinCreate'] = '/join';
$lang['helpItemDescInvite'] = 'Bjud in någon (eg till ett privat rum):';
$lang['helpItemCodeInvite'] = '/invite Användare';
$lang['helpItemDescUninvite'] = 'Upphäv inbjudan:';
$lang['helpItemCodeUninvite'] = '/uninvite Användare';
$lang['helpItemDescLogout'] = 'Logga ut från Chatten:';
$lang['helpItemCodeLogout'] = '/quit';
$lang['helpItemDescPrivateMessage'] = 'Privat meddelande:';
$lang['helpItemCodePrivateMessage'] = '/msg Användare Text';
$lang['helpItemDescQueryOpen'] = 'Öppna privat kanal:';
$lang['helpItemCodeQueryOpen'] = '/query Användare';
$lang['helpItemDescQueryClose'] = 'Stäng privat kanal:';
$lang['helpItemCodeQueryClose'] = '/query';
$lang['helpItemDescAction'] = 'Beskriv händelse:';
$lang['helpItemCodeAction'] = '/action Text';
$lang['helpItemDescDescribe'] = 'Beskriv händelse i privat meddelande:';
$lang['helpItemCodeDescribe'] = '/describe Användare Text';
$lang['helpItemDescIgnore'] = 'Ignorera/acceptera meddelande från användare:';
$lang['helpItemCodeIgnore'] = '/ignore Användare';
$lang['helpItemDescIgnoreList'] = 'Lista ignorerade användare:';
$lang['helpItemCodeIgnoreList'] = '/ignore';
$lang['helpItemDescWhereis'] = 'Visa användare &amp; kanal:';
$lang['helpItemCodeWhereis'] = '/whereis Användarnamn';
$lang['helpItemDescKick'] = 'Sparka användare (Endast moderatorer):';
$lang['helpItemCodeKick'] = '/kick Användare [Minuter bannad]';
$lang['helpItemDescUnban'] = 'Upphäv banning av användare (Endast moderatorer):';
$lang['helpItemCodeUnban'] = '/unban Användare';
$lang['helpItemDescBans'] = 'Lista bannade användare (Endast moderatorer):';
$lang['helpItemCodeBans'] = '/bans';
$lang['helpItemDescWhois'] = 'Visa användares IP (Endast moderatorer):';
$lang['helpItemCodeWhois'] = '/whois Användare';
$lang['helpItemDescWho'] = 'Lista användare online:';
$lang['helpItemCodeWho'] = '/who [Kanalnamn]';
$lang['helpItemDescList'] = 'Lista tillgängliga kanaler:';
$lang['helpItemCodeList'] = '/list';
$lang['helpItemDescRoll'] = 'Rulla tärning:';
$lang['helpItemCodeRoll'] = '/roll [siffra]d[sidor]';
$lang['helpItemDescNick'] = 'Ändra användarnamn:';
$lang['helpItemCodeNick'] = '/nick Användarnamn';
$lang['settings'] = 'Inställningar';
$lang['settingsBBCode'] = 'Aktivera BBCode:';
$lang['settingsBBCodeImages'] = 'Aktivera BBCode bilder:';
$lang['settingsBBCodeColors'] = 'Enable BBCode textfärg:';
$lang['settingsHyperLinks'] = 'Aktivera länkar:';
$lang['settingsLineBreaks'] = 'Aktivera radbryt:';
$lang['settingsEmoticons'] = 'Aktivera smiley\'s:';
$lang['settingsAutoFocus'] = 'Sätt fokus automatiskt på skrivraden:';
$lang['settingsMaxMessages'] = 'Maximalt antal meddelanden i Chatlistan:';
$lang['settingsWordWrap'] = 'Aktivera "wrapping" av långa ord:';
$lang['settingsMaxWordLength'] = 'Maxlängd på ord innan det blir avdelat:';
$lang['settingsDateFormat'] = 'Visningsformat av datum och tid:';
$lang['settingsPersistFontColor'] = 'Behåll textfärg:';
$lang['settingsAudioVolume'] = 'Volym:';
$lang['settingsSoundReceive'] = 'Ljud för inkommande meddelanden:';
$lang['settingsSoundSend'] = 'Ljud för utgående meddelanden:';
$lang['settingsSoundEnter'] = 'Ljud för Logga in/Kanal meddelanden:';
$lang['settingsSoundLeave'] = 'Ljud för Logga ut/lämna Kanal meddelanden:';
$lang['settingsSoundChatBot'] = 'Ljud för Chatbot meddelanden:';
$lang['settingsSoundError'] = 'Ljud för felmeddelanden:';
$lang['settingsSoundPrivate'] = 'Ljud för privata meddelanden:';
$lang['settingsBlink'] = 'Blinka fönstrets titel vid nya meddelanden:';
$lang['settingsBlinkInterval'] = 'Blinkintervall i millisekunder:';
$lang['settingsBlinkIntervalNumber'] = 'Antal blinkintervaller:';
$lang['playSelectedSound'] = 'Spela valt ljud';
$lang['requiresJavaScript'] = 'JavaScript krävs för denna Chat.';
$lang['errorInvalidUser'] = 'Ogiltigt användarnamn.';
$lang['errorUserInUse'] = 'Användarnamnet är redan i bruk.';
$lang['errorBanned'] = 'Användaren eller IP-numret är bannat.';
$lang['errorMaxUsersLoggedIn'] = 'Chatten är full. Max antal användare är inloggade.';
$lang['errorChatClosed'] = 'Chatten är stängd för tillfället.';
$lang['logsTitle'] = 'AJAX Chat - Loggfiler';
$lang['logsDate'] = 'Datum';
$lang['logsTime'] = 'Tid';
$lang['logsSearch'] = 'Sök';
$lang['logsPrivateChannels'] = 'Privata Kanaler';
$lang['logsPrivateMessages'] = 'Privata Meddelanden';
?>
