<?php


namespace App\Model\Manager;

use App\Model\Entity\Category;
use App\Model\Entity\Movie;
use App\Model\Manager\AbstractManager;
use App\Model\Entity\MCUConnection;

class McuManager extends AbstractManager
{
    public function deleteComment($mcuId){
        $db = $this->dbConnect();
        $req = $db->prepare('UPDATE  mcu_connection set justification_comment = NULL WHERE id = ?');
        $req->execute(array($mcuId));

        return $req;
    }
}