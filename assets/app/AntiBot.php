<?php


namespace Acme;

use DNSBL\DNSBL;
use Jaybizzle\CrawlerDetect\CrawlerDetect;

class AntiBot
{

	protected static $dnsbl;


	public static function analysis()
	{
		self::$dnsbl = new DNSBL(array(
			'blacklists' => array(
				"all.s5h.net",
				"b.barracudacentral.org",
				"bl.spamcop.net",
				"blacklist.woody.ch",
				"bogons.cymru.com",
				"cbl.abuseat.org",
				"combined.abuse.ch",
				"db.wpbl.info",
				"dnsbl-1.uceprotect.net",
				"dnsbl-2.uceprotect.net",
				"dnsbl-3.uceprotect.net",
				"dnsbl.anticaptcha.net",
				"dnsbl.dronebl.org",
				"dnsbl.inps.de",
				"dnsbl.sorbs.net",
				"dnsbl.spfbl.net",
				"drone.abuse.ch",
				"duinv.aupads.org",
				"dul.dnsbl.sorbs.net",
				"dyna.spamrats.com",
				"dynip.rothen.com",
				"http.dnsbl.sorbs.net",
				"ips.backscatterer.org",
				"ix.dnsbl.manitu.net",
				"korea.services.net",
				"misc.dnsbl.sorbs.net",
				"noptr.spamrats.com",
				"orvedb.aupads.org",
				"pbl.spamhaus.org",
				"proxy.bl.gweep.ca",
				"psbl.surriel.com",
				"relays.bl.gweep.ca",
				"relays.nether.net",
				"sbl.spamhaus.org",
				"singular.ttk.pte.hu",
				"smtp.dnsbl.sorbs.net",
				"socks.dnsbl.sorbs.net",
				"spam.abuse.ch",
				"spam.dnsbl.anonmails.de",
				"spam.dnsbl.sorbs.net",
				"spam.spamrats.com",
				"spambot.bls.digibase.ca",
				"spamrbl.imp.ch",
				"ubl.lashback.com",
				"ubl.unsubscore.com",
				"virus.rbl.jp",
				"web.dnsbl.sorbs.net",
				"wormrbl.imp.ch",
				"xbl.spamhaus.org",
				"z.mailspike.net",
				"zen.spamhaus.org",
				"zombie.dnsbl.sorbs.net",
			)
		));


			 if(self::$dnsbl->isListed(self::client_ip()) || (new CrawlerDetect)->isCrawler()){
			 	header("Location: https://en.wikipedia.org/wiki/Special:Random");
			 	exit(0);
			 }
	}

	public static function client_ip() {
		$ipaddress = '';
		if (getenv('HTTP_CLIENT_IP'))
			$ipaddress = getenv('HTTP_CLIENT_IP');
		else if(getenv('HTTP_X_FORWARDED_FOR'))
			$ipaddress = getenv('HTTP_X_FORWARDED_FOR');
		else if(getenv('HTTP_X_FORWARDED'))
			$ipaddress = getenv('HTTP_X_FORWARDED');
		else if(getenv('HTTP_FORWARDED_FOR'))
			$ipaddress = getenv('HTTP_FORWARDED_FOR');
		else if(getenv('HTTP_FORWARDED'))
			$ipaddress = getenv('HTTP_FORWARDED');
		else if(getenv('REMOTE_ADDR'))
			$ipaddress = getenv('REMOTE_ADDR');
		else
			$ipaddress = 'UNKNOWN';
		return $ipaddress;
	}

}