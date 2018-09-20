<?php
/**
 * Created by PhpStorm.
 * User: legio
 * Date: 20.09.18
 * Time: 20:40
 */

namespace App\Security\Annotation;


use Doctrine\Common\Annotations\Annotation\Target;

/**
 * Class AuthorizedOnly
 *
 * @Annotation
 * @Target("METHOD")
 * @package App\Security\Annotation
 */
class AuthorizedOnly
{
    public $yourSelf = false;
}