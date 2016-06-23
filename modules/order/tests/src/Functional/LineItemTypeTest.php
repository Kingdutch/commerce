<?php

namespace Drupal\Tests\commerce_order\Functional;

use Drupal\commerce_order\Entity\LineItemType;

/**
 * Tests the commerce_line_item_type entity type.
 *
 * @group commerce
 */
class LineItemTypeTest extends OrderBrowserTestBase {

  /**
   * Tests creating a line item type programmatically and through the add form.
   */
  public function testLineItemTypeCreation() {
    $values = [
      'id' => strtolower($this->randomMachineName(8)),
      'label' => $this->randomMachineName(16),
      'purchasableEntityType' => 'commerce_product_variation',
      'orderType' => 'default',
    ];
    $this->createEntity('commerce_line_item_type', $values);
    $line_item_type = LineItemType::load($values['id']);
    $this->assertEquals($line_item_type->label(), $values['label'], 'The new line item type has the correct label.');
    $this->assertEquals($line_item_type->getPurchasableEntityTypeId(), $values['purchasableEntityType'], 'The new line item type has the correct purchasable entity type.');
    $this->assertEquals($line_item_type->getOrderTypeId(), $values['orderType'], 'The new line item type has the correct order type.');

    $this->drupalGet('admin/commerce/config/line-item-types/add');
    $edit = [
      'id' => strtolower($this->randomMachineName(8)),
      'label' => $this->randomMachineName(16),
      'purchasableEntityType' => 'commerce_product_variation',
      'orderType' => 'default',
    ];
    $this->submitForm($edit, t('Save'));
    $line_item_type = LineItemType::load($edit['id']);
    $this->assertEquals($line_item_type->label(), $edit['label'], 'The new line item type has the correct label.');
    $this->assertEquals($line_item_type->getPurchasableEntityTypeId(), $edit['purchasableEntityType'], 'The new line item type has the correct purchasable entity type.');
    $this->assertEquals($line_item_type->getOrderTypeId(), $edit['orderType'], 'The new line item type has the correct order type.');
  }

  /**
   * Tests updating a line item type through the edit form.
   */
  public function testLineItemTypeEditing() {
    $values = [
      'id' => strtolower($this->randomMachineName(8)),
      'label' => $this->randomMachineName(16),
      'purchasableEntityType' => 'commerce_product_variation',
      'orderType' => 'default',
    ];
    /** @var \Drupal\commerce_order\Entity\LineItemTypeInterface $type */
    $line_item_type = $this->createEntity('commerce_line_item_type', $values);

    $this->drupalGet($line_item_type->toUrl('edit-form'));
    $edit = [
      'label' => $this->randomMachineName(16),
    ];
    $this->submitForm($edit, t('Save'));
    $line_item_type = LineItemType::load($values['id']);
    $this->assertEquals($line_item_type->label(), $edit['label'], 'The label of the line item type has been changed.');
  }

  /**
   * Tests deleting a line item type programmatically and through the form.
   */
  public function testLineItemTypeDeletion() {
    $values = [
      'id' => strtolower($this->randomMachineName(8)),
      'label' => $this->randomMachineName(16),
      'purchasableEntityType' => 'commerce_product_variation',
      'orderType' => 'default',
    ];
    $line_item_type = $this->createEntity('commerce_line_item_type', $values);

    $this->drupalGet($line_item_type->toUrl('delete-form'));
    $this->assertSession()->statusCodeEquals(200);
    $this->assertSession()->pageTextContains(t('This action cannot be undone.'));
    $this->submitForm([], t('Delete'));
    $line_item_type_exists = (bool) LineItemType::load($line_item_type->id());
    $this->assertFalse($line_item_type_exists, 'The line item type has been deleted form the database.');
  }

}
