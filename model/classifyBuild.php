<?php 
class classifyBuild{  
    private $result;//结果数组  
    private $arrSource;//待处理数组  
    public  $sort = false;  
    public function __construct(array $arrSource)  
    {  
        $this->arrSource = $arrSource;  
        $this->result = array();  
    }  
    /** 
     * 在数组$arr一维上查找是否 id 值和$id相同的值 
     * @param  [array]    $arr [结果数组] 
     * @param  [int]      $id  [要查找的id值] 
     * @return [int|null]      [如果存在，则返回该值对应的位置$key；否则，返回null] 
     */  
    private function search($id)  
    {  
        foreach($this->result as $key=>$value){  
            if ($value['id'] == $id) {  
                return $key;  
            }  
        }  
        return null;  
    }  
    /** 
     * 在结果数组中是否有 id值和$pid相同的值 
     * @param  [array] $arr [结果数组] 
     * @param  [int]  $pid  [要查找的pid值] 
     * @return [&$arr|&$pid] [如果存在，则返回结果数组中查找到的该值的引用;如果不存在，则返回$pid的引用] 
     */  
    private function updateArray(&$result, $arrTmp)  
    {  
        foreach($result as $key=>$one){  
            if ($one['id'] == $arrTmp['pid']) {  
                //$result[$key]['childrens'][] = $arrTmp;  
                $this->result_push($result[$key]['childrens'], $arrTmp);  
                return true;  
            } elseif (!empty($one['childrens'])) {  
                $returnValue = $this->updateArray($result[$key]['childrens'], $arrTmp);  
                if (true === $returnValue) {  
                    return true;  
                }  
            }  
        }  
        return false;  
    }  
  
    public function make(){  
        foreach($this->arrSource as $posi=>$oneArr){  
            $arrTmp = array();  
            /** 
             * 处理当前节点 
             * 判断 当前节点在结果数组中是否存在； 
             *  存在，则将当前节点挂载到结果数组的childrens中； 
             *  否则，为父节点生成一个节点，将当前节点挂载在父节点的childrens上，将父节点挂载到结果数组中 
             */  
            $key = $this->search($oneArr['id']);  
            if (!is_null($key) && 0==$oneArr['pid']) {  
                //如果存在本次查找的对象，但是当前对象的pid是0，则说明当前节点没有父节点，而且已经因为在之前被挂载过了，无需再处理  
                continue;  
            } elseif (!is_null($key)) {  
                //如果在结果数组中已经存在id值，单当前节点对象pid不是0，则说明当前节点还有父节点，则取出这个id值，消掉数组中的对应的值  
                $arrTmp = $oneArr;  
                $arrTmp['childrens'] = $this->result[$key]['childrens'];  
                unset($this->result[$key]);  
            } else {//如果在结果数组中不存在对应的id值  
                $arrTmp = $oneArr;  
                $arrTmp['childrens'] = array();  
            }  
  
            /** 
             * 将处理过的当前节点 $arrTmp 根据不同的情况更新到结果数组中 
             */  
            if (0!=$arrTmp['pid']) {  
                //如果父id不是0，则更新当前数组到结果数组中  
                if ( false === $this->updateArray($this->result, $arrTmp)) {//如果更新不成功，说明是没有被收录的父id  
                    $arrTmp = array('id'=>$arrTmp['pid'], 'pid'=>0 , 'childrens'=>array($arrTmp));  
                    //array_push($this->result, $arrTmp);  
                    $this->result_push($this->result, $arrTmp);  
                }  
            } else {  
                //父节点为0，说明当前节点没有父节点，应该挂载在结果数组一维上  
                //array_push($this->result, $arrTmp);  
                $this->result_push($this->result, $arrTmp);  
            }  
        }  
    }  
    /** 
     * 将需要插入的数据更新到结果数组中 
     * @param  [array] $arr       [结果数组] 
     * @param  [array] $pushValue [需要插入的数组] 
     */  
    private function result_push(&$arr, $pushValue)  
    {  
        if (false === $this->sort) {//未开启sort  
            $arr[] = $pushValue;  
        } else {//开启sort  
            $length = 0;  
            foreach($arr as $one){  
                $one['sort'] = isset($one['sort'])?$one['sort']:0;  
                $pushValue['sort'] = isset($pushValue['sort'])?$pushValue['sort']:0;  
                if ($one['sort'] > $pushValue['sort']) {  
                    break;  
                } else {  
                    $length++;  
                }  
            }  
            $before = array_slice($arr, 0, $length);  
            $after  = array_slice($arr, $length, count($arr)-$length);  
            $arr = array_merge($before, array($pushValue), $after);  
        }  
    }  
    public function getResult()  
    {  
        return $this->result;  
    }  
} 
?>