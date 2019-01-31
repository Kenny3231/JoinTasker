<?php

/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
 */

/* * ***************************Includes********************************* */
require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';



class JoinTasker extends eqLogic {
    
        public function preUpdate() {

            if ($this->getConfiguration('key') == '') {
                throw new Exception(__('Le champs clé ne peut être vide',__FILE__));
            }
			if ($this->getConfiguration('deviceid') == '') {
                throw new Exception(__('Le champs deviceid ne peut être vide',__FILE__));
            }

        }    
  
	
	
	
    	public function postSave() {
        
		if (!is_file(dirname(__FILE__) . '/../config/JoinTasker.json')) {
			throw new Exception(__('Pas de fichier Json',__FILE__));
			return;
		}
			
		$content = file_get_contents(dirname(__FILE__) . '/../config/JoinTasker.json');
		if (!is_json($content)) {
			throw new Exception(__('Erreur fichier Json',__FILE__));
			
			return;
		}
		$device = json_decode($content, true);
		if (!is_array($device) || !isset($device['commands'])) {
			return true;
		}
		foreach ($device['commands'] as $command) {
			$cmd = null;
			foreach ($this->getCmd() as $liste_cmd) {
				if ((isset($command['logicalId']) && $liste_cmd->getLogicalId() == $command['logicalId'])
				|| (isset($command['name']) && $liste_cmd->getName() == $command['name'])) {
					$cmd = $liste_cmd;
					break;
				}
			}
			if ($cmd == null || !is_object($cmd)) {
				$cmd = new JoinTaskerCmd();
				$cmd->setEqLogic_id($this->getId());
				utils::a2o($cmd, $command);
				$cmd->save();
			}
		}		  
    }
}

class JoinTaskerCmd extends cmd {
    /*     * *************************Attributs****************************** */


    /*     * ***********************Methode static*************************** */


    /*     * *********************Methode d'instance************************* */

    public function execute($_options = null) {
        
		//Déclaration
		$JoinADDR = 'https://joinjoaomgcd.appspot.com/_ah/api/messaging/v1/sendPush?';		
        $JoinTasker = $this->getEqLogic();
        $key = $JoinTasker->getConfiguration('key');
		$deviceid = $JoinTasker->getConfiguration('deviceid');
		$Ico = $JoinTasker->getConfiguration('ico');
		
		//Si icone
		if ($JoinTasker->getConfiguration('ico')) {
		$Ico = rawurlencode($Ico);	
		}		
		
		// Si message sinon fin
		if (!isset($_options['message'])) {
				return;
		}
		
		$Message = $_options['message'];
		$Message = rawurlencode($Message);
		
		//Si titre
		if (isset($_options['title'])) {
			$Title = $_options['title'];
			$Title = rawurlencode($Title);									
			}
        
		//Choix de l'élément
		switch ($this->getLogicalId()){
			case 'JText':
				$url = $JoinADDR . 'text=' . $Message .'&title='. $Title.'&icon='. $Ico ;								
				break;
				
			case 'Url':          
				$url = $JoinADDR.'url='. $Message  ;
				log::add('JoinTasker','debug','Envoi de l URL: '.$Url);				
				break;
				
			case 'App':
				$url = $JoinADDR.'app='. $Message ;
				log::add('JoinTasker','debug','Envoi de l URL: '.$Url);
				break;
				
			case 'Tel':
				$url = $JoinADDR.'callnumber='. $Message ;
				break;
				
			case 'Sms': 
				$url = $JoinADDR.'smstext='. $Message.'&smsnumber='. $Title  ;
				break;
		}
		//Rajout des identifiants
		$url = $url .'&deviceId=' . trim($deviceid) . '&apikey=' . trim($key) ;
		log::add('JoinTasker', 'debug', 'Commande envoyée : ' . $url);
		//Envoye la requete HTTP
		$request_http = new com_http($url);
		$request_http->exec(30);
    }
    /*     * **********************Getteur Setteur*************************** */
}

?>