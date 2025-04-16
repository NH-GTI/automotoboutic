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
    /*
    * module: gtifeatures
    * date: 2025-04-01 17:32:54
    * version: 1.0.0
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
    /*
    * module: gtifeatures
    * date: 2025-04-01 17:32:54
    * version: 1.0.0
    */
    protected function hideCheckoutStepsBootstrap()
    {
        Tools::redirect($this->context->link->getModuleLink('ps_wirepayment', 'validation'));
    }
}
