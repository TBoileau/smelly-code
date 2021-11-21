<?php

declare(strict_types=1);

namespace App\Factory;

use App\Dto\SmellyCode as SmellyCodeDto;
use App\Entity\Carbon;
use App\Entity\Gist;
use App\Entity\SmellyCode;
use Doctrine\Common\Collections\ArrayCollection;
use Exception;

final class SmellyCodeFactory implements SmellyCodeFactoryInterface
{
    public function createFromDto(SmellyCodeDto $smellyCodeDto): SmellyCode
    {
        if (false === parse_url($smellyCodeDto->url)) {
            throw new Exception('This url is not valid.'); // @codeCoverageIgnore
        }

        $host = parse_url($smellyCodeDto->url, PHP_URL_HOST);

        $smellyCode = match ($host) {
            'carbon.now.sh' => new Carbon(),
            default => new Gist()
        };

        $smellyCode->setUrl($smellyCodeDto->url);
        $smellyCode->setName($smellyCodeDto->name);
        $smellyCode->setTags(new ArrayCollection($smellyCodeDto->tags));

        return $smellyCode;
    }
}
