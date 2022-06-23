<?php

namespace GenesisShirt\Controller;

use GenesisShirt\Model\MetadataModel;
use Think\Controller;

class IndexController extends Controller
{
    function json($return)
    {
        header('Content-Type:application/json; charset=utf-8');
        header('Access-Control-Allow-Methods:*');
        header('Access-Control-Allow-Headers:*');
        header("Access-Control-Request-Headers:*");
        echo json_encode($return);
        exit;
    }

    function redeem($address, $shirt, $name, $phone, $wallet)
    {
        $data = [
            'address' => $address,
            'name' => $name,
            'phone' => $phone,
            'shirt' => $shirt,
            'create_time' => time(),
            'status' => 1,
            'wallet' => $wallet
        ];
        M("GshirtSale")->add($data);
    }

    function erc1155()
    {
        $Metadata = new MetadataModel();
        $image = "https://gateway.pinata.cloud/ipfs/QmS2aPSfLzrEpj9MJQwcwPaxZq5dB6arsieWbjoKDeL5D8";
//        $image = "https://themta.mypinata.cloud/ipfs/QmTwpqR5NjH1McZrMGp5qrwMzhKQ9ENygfnMkiPNN77zjg/2949.png";
        $animation_url = "https://gateway.pinata.cloud/ipfs/QmWaHPdkqpG8eu3M6K3QRJz5rvRLhZgmwF5kPGTFSZnKCj/1155.gif";
        $link = "http://tshit.nftunit.site";
        $name = "NFTUNIT GENESIS SHIRT";
        $description = "THE FIRST IRL COLLECTION OF NFTUNIT COMMUNITY. DESIGN by 0xDomi Holding a Genesis Shirt nft qualifies you to redeem shirt merch in here (tshit.nftunit.site）";
        $data = $Metadata->create($image, $animation_url, $link, $name, $description);
        $this->json($data);
    }

    function erc721($id)
    {
        var_dump($id);
    }

    function erc721DesinerCollctionBlack()
    {
        $Metadata = new MetadataModel();
        $image = "https://gateway.pinata.cloud/ipfs/QmS2aPSfLzrEpj9MJQwcwPaxZq5dB6arsieWbjoKDeL5D8";
        $animation_url = "https://gateway.pinata.cloud/ipfs/QmWaHPdkqpG8eu3M6K3QRJz5rvRLhZgmwF5kPGTFSZnKCj/721%20designer%20collection%28black%29.gif";
        $link = "http://tshit.nftunit.site";
        $name = "designer collection(black)";
        $description = "THE FIRST IRL COLLECTION OF NFTUNIT COMMUNITY. DESIGN by 0xDomi Holding a Genesis Shirt nft qualifies you to redeem shirt merch in here (tshit.nftunit.site）";
        $data = $Metadata->create($image, $animation_url, $link, $name, $description);
        $this->json($data);
    }

    function erc721DesinerCollctionWhite()
    {
        $Metadata = new MetadataModel();
        $image = "https://gateway.pinata.cloud/ipfs/QmS2aPSfLzrEpj9MJQwcwPaxZq5dB6arsieWbjoKDeL5D8";
        $animation_url = "https://gateway.pinata.cloud/ipfs/QmWaHPdkqpG8eu3M6K3QRJz5rvRLhZgmwF5kPGTFSZnKCj/721%20designer%20collection%28white%29.gif`";
        $link = "http://tshit.nftunit.site";
        $name = "designer collection(white)";
        $description = "THE FIRST IRL COLLECTION OF NFTUNIT COMMUNITY. DESIGN by 0xDomi Holding a Genesis Shirt nft qualifies you to redeem shirt merch in here (tshit.nftunit.site）";
        $data = $Metadata->create($image, $animation_url, $link, $name, $description);
        $this->json($data);
    }

    function erc721CommunityPFPCollectionBlack()
    {
        $Metadata = new MetadataModel();
        $image = "https://gateway.pinata.cloud/ipfs/QmS2aPSfLzrEpj9MJQwcwPaxZq5dB6arsieWbjoKDeL5D8";
        $animation_url = "https://gateway.pinata.cloud/ipfs/QmWaHPdkqpG8eu3M6K3QRJz5rvRLhZgmwF5kPGTFSZnKCj/721%20community%20PFP%20collection%28black%29.gif";
        $link = "http://tshit.nftunit.site";
        $name = "community PFP collection(black)";
        $description = "THE FIRST IRL COLLECTION OF NFTUNIT COMMUNITY. DESIGN by 0xDomi Holding a Genesis Shirt nft qualifies you to redeem shirt merch in here (tshit.nftunit.site）";
        $data = $Metadata->create($image, $animation_url, $link, $name, $description);
        $this->json($data);
    }

    function erc721CommunityPFPCollectionWhite()
    {
        $Metadata = new MetadataModel();
        $image = "https://gateway.pinata.cloud/ipfs/QmS2aPSfLzrEpj9MJQwcwPaxZq5dB6arsieWbjoKDeL5D8";
        $animation_url = "https://gateway.pinata.cloud/ipfs/QmWaHPdkqpG8eu3M6K3QRJz5rvRLhZgmwF5kPGTFSZnKCj/721%20community%20PFP%20collection%28white%29.gif";
        $link = "http://tshit.nftunit.site";
        $name = "community PFP collection(white)";
        $description = "THE FIRST IRL COLLECTION OF NFTUNIT COMMUNITY. DESIGN by 0xDomi Holding a Genesis Shirt nft qualifies you to redeem shirt merch in here (tshit.nftunit.site）";
        $data = $Metadata->create($image, $animation_url, $link, $name, $description);
        $this->json($data);
    }
}