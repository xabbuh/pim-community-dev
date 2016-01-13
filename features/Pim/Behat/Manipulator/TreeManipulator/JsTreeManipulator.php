<?php

namespace Pim\Behat\Manipulator\TreeManipulator;

use Behat\Mink\Element\NodeElement;
use Context\Spin\SpinCapableTrait;

/**
 * Js tree lib manipulator to ease the dom manipulation and assertion arround it.
 */
class JsTreeManipulator
{
    use SpinCapableTrait;

    /**
     * @param NodeElement $rootElement
     * @param string      $nodeName
     *
     * @return NodeElement
     */
    public function findNodeInTree(NodeElement $rootElement, $nodeName)
    {
        $node = $this->spin(function () use ($rootElement, $nodeName) {
            return $rootElement->find('css', sprintf('li a:contains("%s")', $nodeName));
        }, sprintf('Unable to find node "%s" in the tree', $nodeName));

        return $node;
    }

    /**
     * @param string $nodeName
     *
     * @return Edit
     */
    public function expandNode(NodeElement $rootElement, $nodeName)
    {
        $node = $this->findNodeInTree($rootElement, $nodeName)->getParent();
        if ($node->hasClass('jstree-closed')) {
            $nodeElement = $this->spin(function () use ($node) {
                return $node->find('css', 'ins');
            });

            $nodeElement->click();
        }
    }
}
