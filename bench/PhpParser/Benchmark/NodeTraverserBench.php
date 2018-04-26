<?php

declare(strict_types=1);

namespace PhpParser\Benchmark;

use PhpBench\Benchmark\Metadata\Annotations\BeforeMethods;
use PhpParser\Node;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\NameResolver;
use PhpParser\ParserFactory;

/**
 * @BeforeMethods({"init"})
 */
final class NodeTraverserBench
{
    /** @var Node[] */
    private $thisClassAst;

    /** @var Node[] */
    private $thisClassAstWithNamesResolved;

    /** @var NodeTraverser */
    private $traverserWithNameResolver;

    /** @var NodeTraverser */
    private $emptyTraverser;

    public function init() : void
    {
        $parser = (new ParserFactory())->create(ParserFactory::ONLY_PHP7);

        $this->traverserWithNameResolver     = new NodeTraverser();
        $this->emptyTraverser                = new NodeTraverser();
        $this->thisClassAst                  = $parser->parse(file_get_contents(__FILE__));
        $this->thisClassAstWithNamesResolved = $parser->parse(file_get_contents(__FILE__));

        $this->traverserWithNameResolver->addVisitor(new NameResolver());
    }

    public function benchEmptyTraversal() : void
    {
        $this->emptyTraverser->traverse($this->thisClassAst);
    }

    public function benchTraversalWithVisitor() : void
    {
        $this->traverserWithNameResolver->traverse($this->thisClassAstWithNamesResolved);
    }
}
