<?php

namespace GenesisShirt\Controller;

use GenesisShirt\Model\MetadataModel;
use Think\Controller;

/**
 * erc721 http://metadata.genesis.domi.nftunit.site/GenesisShirt/index/erc721/id/
 * erc1155 http://metadata.genesis.domi.nftunit.site/GenesisShirt/index/erc1155
 *
 * Rinkeby:
 * GenesisShirt721 0x0c88BE2d77Af4E715a6b93D732c3D9586692c443
 * GenesisShirtRedeemable1155 0x218dBC824de5566343AD3C9BEf9D90d006aC1828
 * usdc 0xc39F044ADe74b2725A63C3c839Bb5745301681E6
 *
 *
 */
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

    function jsonSuccess($data, $message = "")
    {
        $return = [
            'status' => 1,
            'data' => $data,
            'message' => $message
        ];
        $this->json($return);
    }

    function jsonError($message = '', $status = 200)
    {
        $return = [
            'status' => $status,
            'data' => null,
            'message' => $message
        ];
        $this->json($return);
    }

    /**
     *
     *
     * @param $address
     * @param $shirt
     * @param $name
     * @param $phone
     * @param $wallet
     * @param $tokenId
     */
    function redeem($address, $shirt, $name, $phone, $wallet, $tokenId)
    {

        $exist = M("GshirtSale")->where(['tokenId' => $tokenId])->find();
        if ($exist) {
            $this->jsonError("非法操作，#{$tokenId} 已经入库");
        }

        //TODO 过程
        /**
         * 1. 校验wallet 是否等于 ownerOf (tokenId)
         * 2. 校验成功后，改写metadata，保存数据
         */
        $data = [
            'address' => $address,
            'name' => $name,
            'phone' => $phone,
            'shirt' => $shirt,
            'create_time' => time(),
            'status' => 1,
            'wallet' => $wallet,
            'tokenId' => $tokenId,
        ];
        $data['id'] = M("GshirtSale")->add($data);
        $this->jsonSuccess($data);
    }

    function erc1155()
    {
        $Metadata = new MetadataModel();
        $image = "https://gateway.pinata.cloud/ipfs/QmS2aPSfLzrEpj9MJQwcwPaxZq5dB6arsieWbjoKDeL5D8";
//        $image = "https://themta.mypinata.cloud/ipfs/QmTwpqR5NjH1McZrMGp5qrwMzhKQ9ENygfnMkiPNN77zjg/2949.png";
        $animation_url = "https://gateway.pinata.cloud/ipfs/QmWaHPdkqpG8eu3M6K3QRJz5rvRLhZgmwF5kPGTFSZnKCj/1155.gif";
        $link = "http://tshirt.nftunit.site";
        $name = "NFTUNIT GENESIS SHIRT";
        $description = "Genesis Shirt is initiated by the NFTnative community NFTUNIT(tshirt.nftunit.site). There are 4 Genesis Shirt styles. Genesis Designer Collection(white/black) Genesis Community PFP Collection(white/black) The design inspiration comes from the native culture of NFT, and MINT is used to interpret the historical moment we are experiencing, the process of entering the distributed Internet from the real world. All elements of design are origin from the designer. Every ILR will reserve an ERC721 NFT badge, and possible utilities in the future. Design by 0xDomi.eth";
        $data = $Metadata->create($image, $animation_url, $link, $name, $description);
        $this->json($data);
    }

    function erc721($id)
    {
        $data = S("METADATA_{$id}");
        if (!$data) {
            $metadata = M("GshirtSale")->where(['tokenId' => $id])->find();
            $Metadata = new MetadataModel();
            $image = "https://gateway.pinata.cloud/ipfs/QmS2aPSfLzrEpj9MJQwcwPaxZq5dB6arsieWbjoKDeL5D8";
//        $image = "https://themta.mypinata.cloud/ipfs/QmTwpqR5NjH1McZrMGp5qrwMzhKQ9ENygfnMkiPNN77zjg/2949.png";
            $animation_url = "https://gateway.pinata.cloud/ipfs/QmWaHPdkqpG8eu3M6K3QRJz5rvRLhZgmwF5kPGTFSZnKCj/1155.gif";
            $link = "http://tshirt.nftunit.site";
            $name = "NFTUNIT GENESIS SHIRT";
            $description = "THE FIRST IRL COLLECTION OF NFTUNIT COMMUNITY. DESIGN by 0xDomi Holding a Genesis Shirt nft qualifies you to redeem shirt merch in here (tshirt.nftunit.site）";
            $data = $Metadata->create($image, $animation_url, $link, $name, $description);


            if ($metadata != null) {
                if ($metadata['shirt'] == 0) {
                    $data['image'] = 'https://gateway.pinata.cloud/ipfs/QmYnuuTt6rpiseNUcf768uPGTvnaEodmg4218Jwiwx3TjA/dcb.png';
                    $data['animation_url'] = 'https://gateway.pinata.cloud/ipfs/QmWaHPdkqpG8eu3M6K3QRJz5rvRLhZgmwF5kPGTFSZnKCj/721%20designer%20collection%28black%29.gif';
                    $data['name'] = "designer collection(black)";
                } else if ($metadata['shirt'] == 1) {
                    $data['image'] = 'https://gateway.pinata.cloud/ipfs/QmYnuuTt6rpiseNUcf768uPGTvnaEodmg4218Jwiwx3TjA/dcw.png';
                    $data['animation_url'] = 'https://gateway.pinata.cloud/ipfs/QmWaHPdkqpG8eu3M6K3QRJz5rvRLhZgmwF5kPGTFSZnKCj/721%20designer%20collection%28white%29.gif';
                    $data['name'] = "designer collection(white)";
                } else if ($metadata['shirt'] == 2) {
                    $data['image'] = 'https://gateway.pinata.cloud/ipfs/QmYnuuTt6rpiseNUcf768uPGTvnaEodmg4218Jwiwx3TjA/cpcb.png';
                    $data['animation_url'] = 'https://gateway.pinata.cloud/ipfs/QmWaHPdkqpG8eu3M6K3QRJz5rvRLhZgmwF5kPGTFSZnKCj/721%20community%20PFP%20collection%28black%29.gif';
                    $data['name'] = "community PFP collection(black)";
                } else {
                    $data['image'] = 'https://gateway.pinata.cloud/ipfs/QmYnuuTt6rpiseNUcf768uPGTvnaEodmg4218Jwiwx3TjA/cpcw.png';
                    $data['animation_url'] = 'https://gateway.pinata.cloud/ipfs/QmWaHPdkqpG8eu3M6K3QRJz5rvRLhZgmwF5kPGTFSZnKCj/721%20community%20PFP%20collection%28white%29.gif';
                    $data['name'] = "community PFP collection(white)";
                }
                //TODO  以后输出为文件格式
                S("METADATA_{$id}", $data);
            }
        }
        $this->json($data);
    }
}