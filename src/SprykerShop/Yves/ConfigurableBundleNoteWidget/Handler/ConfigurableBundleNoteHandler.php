<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ConfigurableBundleNoteWidget\Handler;

use Generated\Shared\Transfer\ConfiguredBundleNoteRequestTransfer;
use Generated\Shared\Transfer\ConfiguredBundleTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use SprykerShop\Yves\ConfigurableBundleNoteWidget\Dependency\Client\ConfigurableBundleNoteWidgetToConfigurableBundleNoteClientInterface;
use SprykerShop\Yves\ConfigurableBundleNoteWidget\Dependency\Client\ConfigurableBundleNoteWidgetToQuoteClientInterface;

class ConfigurableBundleNoteHandler implements ConfigurableBundleNoteHandlerInterface
{
    /**
     * @var \SprykerShop\Yves\ConfigurableBundleNoteWidget\Dependency\Client\ConfigurableBundleNoteWidgetToConfigurableBundleNoteClientInterface
     */
    protected $configurableBundleNoteClient;

    /**
     * @var \SprykerShop\Yves\ConfigurableBundleNoteWidget\Dependency\Client\ConfigurableBundleNoteWidgetToQuoteClientInterface
     */
    protected $quoteClient;

    public function __construct(
        ConfigurableBundleNoteWidgetToConfigurableBundleNoteClientInterface $configurableBundleNoteClient,
        ConfigurableBundleNoteWidgetToQuoteClientInterface $quoteClient
    ) {
        $this->configurableBundleNoteClient = $configurableBundleNoteClient;
        $this->quoteClient = $quoteClient;
    }

    public function setConfiguredBundleNote(ConfiguredBundleTransfer $configuredBundleTransfer): QuoteResponseTransfer
    {
        $configuredBundleNoteRequestTransfer = (new ConfiguredBundleNoteRequestTransfer())
            ->setConfiguredBundle($configuredBundleTransfer)
            ->setQuote($this->quoteClient->getQuote());

        $quoteResponseTransfer = $this->configurableBundleNoteClient
            ->setConfiguredBundleNote($configuredBundleNoteRequestTransfer);

        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return $quoteResponseTransfer;
        }

        $this->quoteClient->setQuote($quoteResponseTransfer->getQuoteTransfer());

        return $quoteResponseTransfer;
    }
}
