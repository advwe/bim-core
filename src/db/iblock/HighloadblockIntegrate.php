<?php
namespace Bim\Db\Iblock;

\CModule::IncludeModule("highloadblock");
use Bitrix\Highloadblock as HL;

/**
 * Class HighloadblockIntegrate
 * @package Bim\Db\Iblock
 */
class HighloadblockIntegrate
{
    /**
     * Add
     * @param $entityName
     * @param $tableName
     * @return bool
     * @throws \Exception
     */
    public function Add($entityName, $tableName)
    {
        if (empty($entityName)) {
            throw new \Exception('entityName is empty');
        }
        if (empty($tableName)) {
            throw new \Exception('tableName is empty');
        }
        $addFields = array(
            'NAME' => trim($entityName),
            'TABLE_NAME' => trim($tableName)
        );
        $addResult = HL\HighloadBlockTable::add($addFields);
        if (!$addResult->isSuccess()) {
            throw new \Exception($addResult->getErrorMessages());
        }
        return true;
    }


    /**
     * Delete
     * @param $entityName
     * @return bool
     * @throws \Exception
     */
    public function Delete($entityName)
    {
        if (!strlen($entityName)) {
            throw new \Exception('Incorrect entityName param value');
        }
        $filter = array('NAME' => $entityName);
        $hlBlockDbRes = HL\HighloadBlockTable::getList(array(
            "filter" => $filter
        ));
        if (!$hlBlockDbRes->getSelectedRowsCount()) {
            throw new \Exception('Not found highloadBlock with entityName = ' . $entityName);
        }
        $hlBlockRow = $hlBlockDbRes->fetch();
        $entity = HL\HighloadBlockTable::compileEntity($hlBlockRow);
        $entityDataClass = $entity->getDataClass();

        $obList = $entityDataClass::getList();
        if ($obList->getSelectedRowsCount() > 0) {
            throw new \Exception('Unable to remove a highloadBlock[' . $entityName . '], because it has elements');
        }
        $delResult = HL\HighloadBlockTable::delete($hlBlockRow['ID']);
        if (!$delResult->isSuccess()) {
            throw new \Exception($delResult->getErrorMessages());
        }
        return true;
    }

}