<?php

namespace App\Controller\Service;

use Endroid\QrCode\Builder\BuilderInterface;
use Endroid\QrCode\Encoding\Encoding;
use App\Entity\Voucher;
use Symfony\Component\Filesystem\Filesystem;

class QRCodeGenerator
{
    private $builder;
    private $filesystem;

    public function __construct(BuilderInterface $builder, Filesystem $filesystem)
    {
        $this->builder = $builder;
        $this->filesystem = $filesystem;
    }

    public function generateQRCode(Voucher $voucher): string
    {
        $strValue = (string) $voucher->getValue();
        $data = "";
        $code = $voucher->getCode();
        $value = $strValue ; 
        $usageLimit = (string) $voucher->getUsageLimit() ;
        $expiration = $voucher->getExpiration()->format('Y-m-d H:i:s') ;
        $market = $voucher->getMarketRelated()->getName() ;
        $user =  $voucher->getUserWon()->getEmail();
        $data = "Code: " . $code . "\n" . "Value: " . $value . "DT". "\n" . "Usage Limit: " . $usageLimit . "\n" . "Expiration: " . $expiration . "\n" . "Market: " . $market . "\n" . "User: " . $user;
        

        $qrCode = $this->builder
            ->data($data)
            ->encoding(new Encoding('UTF-8'))
            ->build();
        
        $qrName = $voucher->getCode() . '.png';
        $qrPath = \dirname(__DIR__, 3) . '/public/qrcodes/';
        
        // Check if the directory exists, if not, create it
        if (!$this->filesystem->exists($qrPath)) {
            $this->filesystem->mkdir($qrPath);
        }
        
        $qrCode->saveToFile($qrPath . $qrName);
        return $qrCode->getDataUri();
    }
}