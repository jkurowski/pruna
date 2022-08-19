<?php
require_once 'kCMS/Thumbs/ThumbLib.inc.php';
class Model_InvestmentModel  extends Zend_Db_Table_Abstract
{
    protected $_name = 'inwestycje';
    protected $_primary = 'id';

    const LIST_WIDTH = 680;
    const LIST_HEIGHT = 510;

    const HEADER_WIDTH = 2560;
    const HEADER_HEIGHT = 460;

    /**
     * Pokaz wybrana inwestycje po slug
     * @param string $slug
     * @return Object
     */
    public function getByTag(string $slug)
    {
        return $this->fetchRow($this->select()->where('slug = ?', $slug));
    }

    /**
     * Pokaz wybrana inwestycje po id
     * @param int $id
     * @return Object
     */
    public function getById(int $id)
    {
        return $this->fetchRow($this->select()->where('id = ?', $id));
    }

    /**
     * Pokaz liste inwestycji
     * ->addMultiOption('1','Inwestycja w sprzedaży')
     * ->addMultiOption('2','Inwestycja zakończona')
     * ->addMultiOption('3','Inwestycja planowana')
     */
    public function getAll(int $limit = null, int $status = null)
    {
        $query = $this->select()
            ->from(array('n' => $this->_name))
            ->order('n.sort ASC');
        if($limit){
            $query->limit($limit);
        }
        if($status){
            $query->where('status = ?', $status);
        }
        return $this->fetchAll($query);
    }

    /**
     * Dodaj miniaturke na liscie
     * @param $id
     * @param $title
     * @param $file
     * @param null $delete
     * @throws Exception
     */
    public function makeThumb($id, $title, $file, $delete = null)
    {
        $filename = slugImg($title, $file['name']);

        if($delete) {
            $investmentQuery = $this->_db_table->select()
                ->from(array('n' => $this->_name),
                    array(
                        'id',
                        'plik_thumb',
                    ))
                ->where('n.id =?', $id);
            $investment = $this->_db_table->fetchRow($investmentQuery);
            unlink(FILES_PATH . "/inwestycje/miniaturka/" . $investment->plik_thumb);
        }

        move_uploaded_file($file['tmp_name'], FILES_PATH.'/inwestycje/miniaturka/'.$filename);
        $uploadfile = FILES_PATH.'/inwestycje/miniaturka/'.$filename;

        if (file_exists($uploadfile)) {
            chmod($uploadfile, 0755);

            $options = array('jpegQuality' => 80);

            PhpThumbFactory::create($uploadfile, $options)
                ->adaptiveResizeQuadrant(self::LIST_WIDTH, self::LIST_HEIGHT)
                ->save($uploadfile);

            $data = array('plik_thumb' => $filename);
            $this->_db_table->update('inwestycje', $data, 'id = '.$id);
        }
    }

    /**
     * Dodaj obrazek do headera
     * @param $id
     * @param $title
     * @param $file
     * @param null $delete
     * @throws Exception
     */
    public function makeHeader($id, $title, $file, $delete = null)
    {
        $filename = slugImg($title, $file['name']);

        if($delete) {
            $investmentQuery = $this->_db_table->select()
                ->from(array('n' => $this->_name),
                    array(
                        'id',
                        'plik_header',
                    ))
                ->where('n.id =?', $id);
            $investment = $this->_db_table->fetchRow($investmentQuery);
            unlink(FILES_PATH . "/inwestycje/header/" . $investment->plik_header);
        }

        move_uploaded_file($file['tmp_name'], FILES_PATH.'/inwestycje/header/'.$filename);
        $uploadfile = FILES_PATH.'/inwestycje/header/'.$filename;

        if (file_exists($uploadfile)) {
            chmod($uploadfile, 0755);

            $options = array('jpegQuality' => 80);

            PhpThumbFactory::create($uploadfile, $options)
                ->adaptiveResizeQuadrant(self::HEADER_WIDTH, self::HEADER_HEIGHT)
                ->save($uploadfile);

            $data = array('plik_header' => $filename);
            $this->_db_table->update('inwestycje', $data, 'id = '.$id);
        }
    }

}