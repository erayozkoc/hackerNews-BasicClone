<?php
$fgc=file_get_contents('https://hacker-news.firebaseio.com/v0/newstories.json?print=pretty');
$data =json_decode($fgc,true);
$items = array_slice($data, 0,20);//Güncel ilk 20 haber id alma
asort($items);

$order = 1;
$timeindex =0;


$liste=[];
asort($liste);
foreach($items as $val){
    $fgc2=file_get_contents("https://hacker-news.firebaseio.com/v0/item/" . $val . ".json?print=pretty");//Güncel ilk 20 haber idsini apiye yönlendirme
    $fgc2=str_replace('},]',"}]",$fgc2);

    $data2=json_decode($fgc2,true);
    array_unshift($liste,$data2);
}



$timeArray=[];//Bu listeye şimdi zmandan apiden gelen zamanların farkını attım
foreach($liste as $time){
    $nowtime = time();
    $different =$nowtime-$time['time'];
    array_unshift($timeArray,$different);

}



//Burada
$differenceArray=[];//Zaman farkını dakika olarak listeye attım
for($i=0; $i<count($timeArray); $i++){

    $minutedifference = floor($timeArray[$i] / 60);
    array_unshift($differenceArray,$minutedifference);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" >
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" />

    <title>HACKER NEWS</title>
</head>
<style>
    *{
    box-sizing: border-box;
    margin: 0;
    padding:0;


}
    .card{
        width: 550px;
        height: 520px;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%,-50%);
        overflow: scroll;
    }

    .card img{
        width: 30px;
        border-radius: 4px;
    }
    .card .top{
        color: orange;
         font-weight: bold;
        background-color: white;
        padding: 10px;
        display: flex;
       align-items: center;
    }
    .card h5{
        margin-top: 4px;
    }
    .card .order{
        text-align: center;
        color: #fff;
        font-weight: bold;
        width: 40px;
        height: 40px;
        border-radius: 4px;
        background-color: rgb(255, 165, 0);
        border: solid 2px rgb(255, 165, 0);
        font-size: 20px;
    }
    .card .title{
        font-size: 16px;
        font-weight: 500;
        margin-left: 10px;
    }
    .card .story-footer{
        margin-left: 44px;
        color: gray;
        font-weight: 400;
    }
    .card::-webkit-scrollbar {
      width: 7px;
    }
    .card::-webkit-scrollbar-thumb {
background-color: darkgrey;
border-radius: 4px;
outline: 1px solid slategrey;
}
.card li{
    list-style-type: none;
    background-color: white;
    margin-bottom: 18px;

}
i{
    margin-left: 2px;
    font-size: 18px;
    color: black;
}



</style>
<body>
            <div class="card bg-light" >
                <div class="card-body">
                    <div class="top"><img src="./icon.png" alt="">
                        <a href="./index.php" class="nav-link"><h5 class="mx-2">HACKER NEWS</h5></a>
                       <div class="icon"><i class="fa fa-fw fa-angle-down" aria-hidden="true"></i></div>

                    </div>
                        <ul class="list-group mt-3">

                        <?php foreach($liste as $item):?>
                            <li class=" p-2 " id="<?php echo $item['id']?>">
                                <div class="story-title d-flex">
                                    <div class="order"><?php echo $order++?></div>
                                    <div class="title">
                                    <a href="<?php if(isset($item['url'])){  ///Bazı linklerde url bulunmuyor oyüzden isset kullandım
                                        echo $item['url'];} ?>" class="nav-link" target="_blank"><?php echo $item['title'] ?></a>
                                    </div>
                                </div>
                                <div class="story-footer ">
                                    <span id="points"><?php echo $item['score']?> <b></b>points</span>
                                      <b>|</b>
                                    <span id="comments"><?php
                                    if(isset($item['descendants'])){
                                        echo ($item['descendants']).'  comments'; //Yeni haberler olduğu için yorum olmuyor keyi bulamayıp hata verdiği için isset kullandım.
                                    }
                                    else{
                                        echo '0 comments';

                                    }
                                     ?></span>
                                      <b>|</b>
                                    <span id="time"><?php echo $differenceArray[$timeindex++].'minute ago';?></span>
                                       <b>|</b>
                                    <span id="by">by<b> </b><?php echo $item['by']?></span>
                                </div>
                            </li>
                            <?php endforeach; ?>

                        </ul>
                  </div>





            </div>




</body>
</html>







