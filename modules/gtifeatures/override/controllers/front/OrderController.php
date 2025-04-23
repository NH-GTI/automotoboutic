<?php
/** GTI Features
 * 
 */

class OrderController extends OrderControllerCore
{
    /**
     * overridden bootstrap
     *
     * @return void
     */
    protected function bootstrap()
    {
        if (Configuration::get('GTIFEATURES_HIDECHECKOUTSTEPS')) {
            return $this->hideCheckoutStepsBootstrap();
        }
        parent::bootstrap();
    }

    /**
     * Skip ALL checkout steps and validate order
     *
     * @return void
     */
    protected function hideCheckoutStepsBootstrap()
    {
        // Autoredirect to payment validation
        Tools::redirect($this->context->link->getModuleLink('ps_wirepayment', 'validation'));
    }
}
