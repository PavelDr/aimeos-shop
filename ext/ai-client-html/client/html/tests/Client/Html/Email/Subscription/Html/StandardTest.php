<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2018
 */


namespace Aimeos\Client\Html\Email\Subscription\Html;


class StandardTest extends \PHPUnit\Framework\TestCase
{
	private static $productItem;
	private static $addressItem;
	private $object;
	private $context;
	private $emailMock;


	public static function setUpBeforeClass()
	{
		$context = \TestHelperHtml::getContext();

		$manager = \Aimeos\MShop\Factory::createManager( $context, 'order/base' );

		$search = $manager->createSearch();
		$search->setConditions( $search->compare( '==', 'order.base.price', '53.50' ) );
		$result = $manager->searchItems( $search, ['order/base/address', 'order/base/product'] );

		if( ( $baseItem = reset( $result ) ) === false ) {
			throw new \RuntimeException( 'No order base item found' );
		}

		foreach( $baseItem->getProducts() as $product )
		{
			if( $product->getProductCode() === 'CNC' ) {
				self::$productItem = $product;
			}
		}

		self::$addressItem = $baseItem->getAddress( \Aimeos\MShop\Order\Item\Base\Address\Base::TYPE_PAYMENT );
	}


	protected function setUp()
	{
		$this->context = \TestHelperHtml::getContext();
		$this->emailMock = $this->getMockBuilder( '\\Aimeos\\MW\\Mail\\Message\\None' )->getMock();

		$view = \TestHelperHtml::getView( 'unittest', $this->context->getConfig() );
		$view->extOrderProductItem = self::$productItem;
		$view->extAddressItem = self::$addressItem;
		$view->addHelper( 'mail', new \Aimeos\MW\View\Helper\Mail\Standard( $view, $this->emailMock ) );

		$this->object = new \Aimeos\Client\Html\Email\Subscription\Html\Standard( $this->context );
		$this->object->setView( $view );
	}


	protected function tearDown()
	{
		unset( $this->object );
	}


	public function testGetBody()
	{
		$ds = DIRECTORY_SEPARATOR;
		$file = '..' . $ds . 'themes' . $ds . 'elegance' . $ds . 'media' . $ds . 'aimeos.png';
		$this->context->getConfig()->set( 'client/html/email/logo', $file );

		$this->emailMock->expects( $this->once() )->method( 'embedAttachment' )
			->will( $this->returnValue( 'cid:123-unique-id' ) );

		$this->emailMock->expects( $this->once() )->method( 'setBodyHtml' )
			->with( $this->matchesRegularExpression( '#<html>.*<title>E-mail notification</title>.*<meta.*Aimeos.*<body>#smu' ) );

		$this->object->setView( $this->object->addData( $this->object->getView() ) );
		$output = $this->object->getBody();

		$this->assertStringStartsWith( '<html>', $output );
		$this->assertContains( 'cid:123-unique-id', $output );

		$this->assertContains( '<p class="email-common-salutation', $output );

		$this->assertContains( '<p class="email-common-intro', $output );
		$this->assertContains( 'The subscription', $output );

		$this->assertContains( '<div class="common-summary-detail common-summary container content-block">', $output );
		$this->assertContains( 'Cafe Noire Cappuccino', $output );

		$this->assertContains( '<p class="email-common-outro', $output );
		$this->assertContains( 'If you have any questions', $output );
	}


	public function testGetSubClientInvalid()
	{
		$this->setExpectedException( '\\Aimeos\\Client\\Html\\Exception' );
		$this->object->getSubClient( 'invalid', 'invalid' );
	}


	public function testGetSubClientInvalidName()
	{
		$this->setExpectedException( '\\Aimeos\\Client\\Html\\Exception' );
		$this->object->getSubClient( '$$$', '$$$' );
	}


	public function testProcess()
	{
		$this->object->process();
	}
}
