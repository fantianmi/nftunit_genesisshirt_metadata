<?php

namespace GenesisShirt\Controller;

use GenesisShirt\Model\MetadataModel;
use Think\Controller;

class IndexController extends Controller
{

    function erc1155()
    {
        $Metadata = new MetadataModel();
        $image = "https://gateway.pinata.cloud/ipfs/QmS2aPSfLzrEpj9MJQwcwPaxZq5dB6arsieWbjoKDeL5D8";
        $animation_url = "https://gateway.pinata.cloud/ipfs/QmWaHPdkqpG8eu3M6K3QRJz5rvRLhZgmwF5kPGTFSZnKCj/1155.gif";
        $link = "http://tshit.nftunit.site";
        $name = "NFTUNIT GENESIS SHIRT";
        $description = "THE FIRST IRL COLLECTION OF NFTUNIT COMMUNITY. 
                        DESIGN by 0xDomi 
                        Holding a Genesis Shirt nft qualifies you to redeem shirt merch in here (tshit.nftunit.site）";
        $data = $Metadata->create($image, $animation_url, $link, $name, $description);
        echo json_encode($data);
    }

    function erc721DesinerCollctionBlack()
    {

    }

    function erc721DesinerCollctionWhite()
    {

    }

    function erc721CommunityPFPCollectionBlack()
    {

    }

    function erc721CommunityPFPCollectionWhite()
    {

    }
}