<?php

namespace C5Deeply;

class Analyzer
{
    /**
     * @var Concrete\Core\Database\Connection\Connection|ADODB_mysqli
     */
    private $cn;

    /**
     * @var int
     */
    private $indent;

    /**
     * @var callable|null
     */
    private $writer;
    /**
     *
     */
    public function __construct($writer = null)
    {
        if (class_exists('\Loader', true)) {
            $this->cn = \Loader::db();
        } else {
            $this->cn = \Database::connection();
        }
        $this->indent = 0;
        $this->writer = is_callable($writer) ? $writer : null;
    }

    /**
     * @return array[]
     */
    private function getBlockTypes()
    {
        static $result;
        if (!isset($result)) {
            $btList = array();
            foreach ($this->cn->GetAll('select btID, btHandle as handle, btName as name from BlockTypes') as $bt) {
                $btID = (int) $bt['btID'];
                unset($bt['btID']);
                $btList[$btID] = $bt;
            }
            $result = $btList;
        }

        return $result;
    }

    /**
     * @param string $w
     * @param bool $eol
     */
    private function write($w, $eol = true)
    {
        $s = str_repeat(' ', 3 * $this->indent).$w.($eol ? "\n" : '');
        if (isset($writer)) {
            $writer($s);
        } else {
            echo $s;
        }
    }

    /**
     * @param int $bID
     */
    public function inspectBlock($bID)
    {
        $bID = (int) $bID;
        $this->write("Block with ID $bID");
        ++$this->indent;
        $b = $this->cn->GetRow('select btID from Blocks where bID = ?', array($bID));
        if (empty($b)) {
            $this->write('BLOCK NOT FOUND!');
        } else {
            $btID = (int) $b['btID'];
            $blockTypes = $this->getBlockTypes();
            if (!isset($blockTypes[$btID])) {
                $this->write("BLOCK TYPE WITH ID $btID NOT FOUND!");
            } else {
                $this->write('Type handle: '.$blockTypes[$btID]['handle']);
                $this->write('Type name  : '.$blockTypes[$btID]['name']);
            }
            $cvbList = $this->cn->GetAll('select cID, cvID, arHandle from CollectionVersionBlocks where bID = ? order by cvID desc', array($bID));
            if (empty($cvbList)) {
                $this->write('BLOCK NOT FOUND IN ANY COLLECTION!');
            } else {
                $this->write('Block found here:');
                ++$this->indent;
                foreach ($cvbList as $cvb) {
                    $this->inspectArea($cvb['cID'], $cvb['cvID'], $cvb['arHandle']);
                }
                --$this->indent;
            }
        }
        --$this->indent;
    }

    /**
     * @param int $cID
     * @param int $cvID
     * @param string $arHandle
     */
    public function inspectArea($cID, $cvID, $arHandle)
    {
        $cID = (int) $cID;
        $cvID = (int) $cvID;
        $arHandle = (string) $arHandle;
        $this->write("Area \"$arHandle\" of:");
        ++$this->indent;
        $this->inspectCollection($cID, $cvID);
        --$this->indent;
    }

    /**
     * @param int $cID
     * @param int $cvID
     */
    public function inspectCollection($cID, $cvID)
    {
        $this->write("Collection $cID @ version $cvID");
        ++$this->indent;
        $cID = (int) $cID;
        $cvID = (int) $cvID;
        $c = $this->cn->GetRow('
            select
                CollectionVersions.cvName,
                CollectionVersions.cvHandle,
                CollectionVersions.cvComments,
                CollectionVersions.cvIsApproved,
                PagePaths.cPath
            from
                CollectionVersions
                left join PagePaths
                    on CollectionVersions.cID = PagePaths.cID
            where
                CollectionVersions.cID = ?
                and CollectionVersions.cvID = ?
            order by
                PagePaths.ppIsCanonical desc
        ', array($cID, $cvID));
        if (empty($c)) {
            $this->write("COLLECTION VERSION $cvID NOT FOUND FOR COLLECTION $cID!");
        } else {
            $this->write('Name    : '.((string) $c['cvName']));
            $this->write('Handle  : '.((string) $c['cvHandle']));
            $this->write('Approved: '.($c['cvIsApproved'] ? 'yes' : 'no'));
            $this->write('Path    : '.((string) $c['cPath']));
            $this->write('comments: '.((string) $c['cvComments']));
        }
        --$this->indent;
    }
}
