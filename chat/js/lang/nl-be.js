/*
 * @package AJAX_Chat
 * @author Sebastian Tschan
 * @author Nic Mertens
 * @copyright (c) Sebastian Tschan
 * @license Modified MIT License
 * @link https://blueimp.net/ajax/
 */

// Ajax Chat language Object:
var ajaxChatLang = {

  login: '%s komt in het chatkanaal.',
  logout: '%s  verlaat het chatkanaal.',
  logoutTimeout: '%s verlaat het chatkanaal (Timeout).',
  logoutIP: '%s is afgemeld (Ongeldig IP address).',
  logoutKicked: '%s is afgemeld (Kick door admin of moderator).',
  channelEnter: '%s komt het kanaal binnen.',
  channelLeave: '%s verlaat het kanaal.',
  privmsg: '(fluistert)',
  privmsgto: '(fluistert naar %s)',
  invite: '%s nodigt je uit om naar %s te komen.',
  inviteto: 'Je uitnodiging naar %s om het kanaal %s te bezoeken, werd verstuurd.',
  uninvite: '%s annuleert de uitnodiging van kanaal %s.',
  uninviteto: 'De annulatie van je uitnodiging aan %s voor het kanaal %s werd verstuurd.',
  queryOpen: 'Privékanaal geopend naar %s.',
  queryClose: 'Privékanaal naar %s wordt gesloten.',
  ignoreAdded: '%s is toegevoegd aan de negeer lijst.',
  ignoreRemoved: '%s werd verwijderd van de negeerlijst.',
  ignoreList: 'Genegeerde gebruikers:',
  ignoreListEmpty: 'Er zijn geen genegeerde gebruikers.',
  who: 'Online gebruikers:',
  whoChannel: 'Online gebruikers in channel %s:',
  whoEmpty: 'Er zijn geen gebruikers in het gekozen kanaal.',
  list: 'Beschikbare kanalen:',
  bans: 'Gebande gebruikers:',
  bansEmpty: 'Er zijn geen gebande gebruikers.',
  unban: 'Ban van gebruiker %s opgeheven.',
  whois: 'Gebruiker %s - IP adres:',
  whereis: 'User %s is in channel %s.',
  roll: '%s smijt %s en krijgt %s.',
  nick: '%s heet nu %s.',
  toggleUserMenu: 'Open menu voor gebruiker %s',
  userMenuLogout: 'Afmelden',
  userMenuWho: 'Lijst online gebruikers',
  userMenuList: 'Lijst beschikbaare kanalen',
  userMenuAction: 'Beschrijf actie',
  userMenuRoll: 'Rol dobbelsteen',
  userMenuNick: 'Wijzig gebruikersnaam',
  userMenuEnterPrivateRoom: 'Ga privékanaal binnen',
  userMenuSendPrivateMessage: 'Stuur privé bericht',
  userMenuDescribe: 'Stuur privé actie',
  userMenuOpenPrivateChannel: 'Open privé kanaal',
  userMenuClosePrivateChannel: 'Sluit privé kanaal',
  userMenuInvite: 'Nodig uit',
  userMenuUninvite: 'Uitnodiging intrekken',
  userMenuIgnore: 'Negeren/aanvaarden',
  userMenuIgnoreList: 'Lijst genegeerde gebruikers',
  userMenuWhereis: 'Toon kanaal',
  userMenuKick: 'Verwijderen/Verbannen',
  userMenuBans: 'Lijst verbande gebruikers',
  userMenuWhois: 'Toon IP',
  unbanUser: 'Gebande gebruiker %s terug toelaten',
  joinChannel: 'Betreedt kanaal %s',
  cite: '%s zei:',
  urlDialog: 'Gelieve het (URL) adres van de webpagina in te geven:',
  deleteMessage: 'Verwijder dit chat bericht',
  deleteMessageConfirm: 'Bent u zeker dat u dit bericht wil verwijderen?',
  errorCookiesRequired: 'Cookies moeten aangeschakeld zijn voor deze chat.',
  errorUserNameNotFound: 'Fout: Gebruiker %s werd niet gevonden.',
  errorMissingText: 'Fout: Ontbrekende berichttekst.',
  errorMissingUserName: 'Fout: Ontbrekende Gebruikersnaam.',
  errorInvalidUserName: 'Error: Invalid username.',
  errorUserNameInUse: 'Error: Gebruikersnaam wordt al gebruikt',
  errorMissingChannelName: 'Fout: Ontbrekende kanaalnaam.',
  errorInvalidChannelName: 'Fout: Ongeldige kanaalnaam: %s',
  errorPrivateMessageNotAllowed: 'Error: Private berichten zijn niet toegestaan.',
  errorInviteNotAllowed: 'Fout: Je bent niet geautoriseerd om iemand uit te nodigen naar dit kanaal.',
  errorUninviteNotAllowed: 'Fout: Je bent niet geautoriseerd om een uitnodiging naar iemand te annuleren op dit kanaal.',
  errorNoOpenQuery: 'Fout: Er is geen privékanaal geopend.',
  errorKickNotAllowed: 'Fout: Je ben niet geautoriseerd om %s te kicken.',
  errorCommandNotAllowed: 'Fout: Opdracht is niet toegestaan: %s',
  errorUnknownCommand: 'Fout: Onbekende opdracht: %s',
  errorMaxMessageRate: 'Error: Je hebt de limiet voor het aantal berichten per minuut overschreven.',
  errorConnectionTimeout: 'Fout: Connection timeout. Gelieve opnieuw te proberen.',
  errorConnectionStatus: 'Fout: Verbindingsstatus: %s',
  errorSoundIO: 'Error: Geluidsbestand kon niet geladen worden (Flash IO Error).',
  errorSocketIO: 'Error: Verbinding met Socket server is verloren (Flash IO Error).',
  errorSocketSecurity: 'Error: Verbinding met Socket server is verloren (Flash Security Error).',
  errorDOMSyntax: 'Error: Ongeldige DOM Syntax (DOM ID: %s).'

}