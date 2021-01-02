<?php
namespace Concrete\Package\CommunityStore\Src\CommunityStore\Discount;

use Concrete\Package\CommunityStore\Src\CommunityStore\Discount\DiscountRule as StoreDiscountRule;
use Doctrine\ORM\Mapping\Column;
use Database;
use Core;
use Session;

/**
 * @Entity
 * @Table(name="CommunityStoreDiscountCodes")
 */
class DiscountCode
{
    /**
     * @Id @Column(type="integer")
     * @GeneratedValue
     */
    protected $dcID;

    /**
     * @ManyToOne(targetEntity="Concrete\Package\CommunityStore\Src\CommunityStore\Discount\DiscountRule", inversedBy="codes")
     * @JoinColumn(name="drID", referencedColumnName="drID", onDelete="CASCADE")
     */
    private $discountRule;

    /**
     * @Column(type="string")
     */
    protected $dcCode;

    /**
     * @Column(type="integer", nullable=true)
     */
    protected $oID;

    /**
     * @Column(type="datetime")
     */
    protected $dcDateAdded;

    /**
     * @return mixed
     */
    public function getID()
    {
        return $this->dcID;
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->dcCode;
    }

    /**
     * @param mixed $dcCode
     */
    public function setCode($dcCode)
    {
        $this->dcCode = $dcCode;
    }

    /**
     * @return mixed
     */
    public function getDiscountRule()
    {
        return $this->discountRule;
    }

    /**
     * @param mixed $discountRule
     */
    public function setDiscountRule($discountRule)
    {
        $this->discountRule = $discountRule;
    }

    /**
     * @return mixed
     */
    public function getOID()
    {
        return $this->oID;
    }

    /**
     * @param mixed $oID
     */
    public function setOID($oID)
    {
        $this->oID = $oID;
    }

    /**
     * @return mixed
     */
    public function getDateAdded()
    {
        return $this->dcDateAdded;
    }

    public function isUsed()
    {
        return $this->oID > 0;
    }

    /**
     * @param mixed $dcDateAdded
     */
    public function setDateAdded($dcDateAdded)
    {
        $this->dcDateAdded = $dcDateAdded;
    }

    public static function getByID($dcID)
    {
        $em = \ORM::entityManager();
        return $em->find(get_class(), $dcID);
    }

    public static function getByCode($code)
    {
        $em = \ORM::entityManager();
        return $em->getRepository(get_class())->findOneBy(array('dcCode' => $code));
    }

    public static function add($discountRule, $code)
    {
        $discountCode = new self();
        $discountCode->setDiscountRule($discountRule);
        $discountCode->setCode($code);
        $discountCode->setDateAdded(new \DateTime());
        $discountCode->save();

        return $discountCode;
    }

    public function save()
    {
        $em = \ORM::entityManager();
        $em->persist($this);
        $em->flush();
    }

    public function delete()
    {
        $em = \ORM::entityManager();
        $em->remove($this);
        $em->flush();
    }

    public static function validate($args)
    {
        $e = Core::make('helper/validation/error');

        return $e;
    }

    public static function storeCartCode($code)
    {
        $rule = StoreDiscountRule::findDiscountRuleByCode($code);

        if (!empty($rule)) {
            Session::set('communitystore.code', $code);

            return true;
        }

        return false;
    }

    public static function hasCartCode()
    {
        return (bool) Session::get('communitystore.code');
    }

    public static function getCartCode()
    {
        return Session::get('communitystore.code');
    }

    public static function clearCartCode()
    {
        Session::set('communitystore.code', '');
    }
}
