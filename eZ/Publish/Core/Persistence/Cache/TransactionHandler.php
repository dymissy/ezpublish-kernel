<?php
/**
 * File containing the Persistence Transaction Cache Handler class
 *
 * @copyright Copyright (C) 1999-2014 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace eZ\Publish\Core\Persistence\Cache;

use eZ\Publish\SPI\Persistence\TransactionHandler as TransactionHandlerInterface;

/**
 * Persistence Transaction Cache Handler class
 */
class TransactionHandler implements TransactionHandlerInterface
{
    /**
     * @var TransactionHandler
     */
    protected $innerTransactionHandler;

    /**
     * @var \eZ\Publish\Core\Persistence\Cache\CacheServiceDecorator
     */
    protected $cache;

    /**
     * @var \eZ\Publish\Core\Persistence\Cache\PersistenceLogger
     */
    protected $logger;

    /**
     * Construct the class
     *
     * @param CacheServiceDecorator $cache
     * @param \eZ\Publish\SPI\Persistence\TransactionHandler $transactionHandler
     * @param PersistenceLogger $logger
     */
    public function __construct(
        CacheServiceDecorator $cache,
        TransactionHandlerInterface $transactionHandler,
        PersistenceLogger $logger
    )
    {
        $this->cache = $cache;
        $this->innerTransactionHandler = $transactionHandler;
        $this->logger = $logger;
    }

    /**
     * Begin transaction
     *
     * @todo Consider to either disable cache or layer it with in-memory cache per transaction, last layer would be the
     *       normal layer. At the moment *all* cache is cleared on rollback for simplicity, as they are not frequent.
     *
     * Begins an transaction, make sure you'll call commit or rollback when done,
     * otherwise work will be lost.
     */
    public function beginTransaction()
    {
        $this->logger->logCall( __METHOD__ );
        $this->innerTransactionHandler->beginTransaction();
    }

    /**
     * Commit transaction
     *
     * Commit transaction, or throw exceptions if no transactions has been started.
     *
     * @throws \RuntimeException If no transaction has been started
     */
    public function commit()
    {
        $this->logger->logCall( __METHOD__ );
        $this->innerTransactionHandler->commit();
    }

    /**
     * Rollback transaction
     *
     * Rollback transaction, or throw exceptions if no transactions has been started.
     *
     * @throws \RuntimeException If no transaction has been started
     */
    public function rollback()
    {
        $this->logger->logCall( __METHOD__ );
        // {@see beginTransaction()}
        $this->cache->clear();
        $this->innerTransactionHandler->rollback();
    }
}