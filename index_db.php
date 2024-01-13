<?php

$ref = 'Hello World';
$str = 'Hell';

class PPSM
{
    
    private $db_padding = ['$', '%', '!', '^', '&', '#'];
    private $q_gram_str = [];
    private $q_gram_ref = [];
    private $hash_str = [];
    private $hash_ref = [];
    private $table_bit_ref = [];
    private $table_bit_str = [];
    private $db_A;
    private $db_B;

    private $result = [];
    public function __construct(private $ref, private $str)
    {
        $servername = "localhost";

        $username = "root";

        $password = "";

        $dbname = "PPSM";
        
        try {
          $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

          $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

          $stmt_A = $conn->prepare("SELECT * FROM ownerA");

          $stmt_A->execute();

          $stmt_B = $conn->prepare("SELECT * FROM ownerB");

          $stmt_B->execute();
        
          // set the resulting array to associative
          $result_A = $stmt_A->fetch(PDO::FETCH_ASSOC);
          
          $result_B = $stmt_B->fetch(PDO::FETCH_ASSOC);

          $this->db_A = $result_A;

          $this->db_B = $result_B;

        } catch(PDOException $e) {

          echo "Error: " . $e->getMessage();

        }
    }

    public function owner()
    {

        $this->str = $this->db_padding[get_rand($this->db_A) . $this->str . get_rand($this->db_A)];

        $this->ref = $this->db_padding[get_rand($this->db_B) . $this->ref . get_rand($this->db_B))];

        return $this;

    }

    public function createQGramStr()
    {

        $i = 0;

        while ($i < strlen($this->str)) {

            $str_temp = '';

            $str_temp .= $this->str[$i];
             
            if ( ! $i <= strlen($this->str)) {

                $str_temp .= $this->str[$i + 1];

            }

            $this->q_gram_str[$i] = $str_temp;

            $i++;

        }

        unset($this->q_gram_str[count($this->q_gram_str)-1]);

        return $this;

    }

    public function createQGramRef()
    {

        $i = 0;

        while ($i < strlen($this->ref)) {

            $ref_temp = '';

            $ref_temp .= $this->ref[$i];

            if ( ! $i <= strlen($this->ref)) {

                $ref_temp .= $this->ref[$i + 1];

            }

            $this->q_gram_ref[$i] = $ref_temp;

            $i++;

        }

        unset($this->q_gram_ref[count($this->q_gram_ref)-1]);

        return $this;

    }

    public function hashFunctionStr()
    {
        for ($i = 1 ; $i <= count($this->q_gram_str)-1 ; $i++) {

            $this->hash_str[] = md5($this->q_gram_str[$i]);

        }

        return $this;
    }

    public function hashFunctionRef()
    {
        for ($i = 1 ; $i <= count($this->q_gram_ref)-2 ; $i++) {

            $this->hash_ref[] = md5($this->q_gram_ref[$i]);

        }

        return $this;
    }

    public function getStr()
    {
        return $this->str;
    }

    public function getRef()
    {
        return $this->ref;
    }
    public function getQ_gram_str()
    {
        return $this->q_gram_str;
    }

    public function getQ_gram_ref()
    {
        return $this->q_gram_ref;
    }

    public function getHash_str()
    {
        return $this->hash_str;
    }

    public function getHash_ref()
    {
        return $this->hash_ref;
    }

    public function createBitTableStr()
    {
        for ($i = 1 ; $i <= count($this->q_gram_str)-2 ; $i++) {

            $v = unpack('H*', $this->q_gram_str[$i]);

            $this->table_bit_str[] = base_convert($v[1], 16, 2);;

        }
        return $this;
    }

    public function createBitTableRef()
    {
        for ($i = 1 ; $i <= count($this->q_gram_ref)-2 ; $i++) {

            $v = unpack('H*', $this->q_gram_ref[$i]);

            $this->table_bit_ref[] = base_convert($v[1], 16, 2);;

        }
        return $this;
    }

    public function getTable_bit_ref()
    {
        return $this->table_bit_ref;
    }

    public function getTable_bit_str()
    {
        return $this->table_bit_str;
    }

    public function algorithmBitFast()
    {


        for ($i = 0 ; $i <= count($this->table_bit_ref)-1 ; $i++) {

            for ($j = 0 ; $j <= count($this->table_bit_str)-1 ; $j++) {


                if ($this->table_bit_ref[$i] == $this->table_bit_str[$j]) {

                    $this->result[] = $this->q_gram_str[$j];

                }

            }

        }

        return $this;
    }

    private function creatString(array $data) : string
    {
        $new_data = '';

        for ($i = 1 ; $i <= count($data) ; $i++) {

            $new_data .= $data[$i];

        }

        return $new_data;
    }

    public function getResult()
    {
        return $this->result;
    }
}


$PPSM = new PPSM($ref, $str);

print_r($PPSM->owner()
             ->createQGramRef()
             ->createQGramStr()
             ->hashFunctionStr()
             ->hashFunctionRef()
             ->createBitTableStr()
             ->createBitTableRef()
             ->algorithmBitFast()
             ->getResult());

