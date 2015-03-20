<?php

namespace Bolt\Response;

use Symfony\Component\HttpFoundation\Response;

/**
 * BoltResponse represents a prepared Bolt HTTP response.
 *
 * A StreamedResponse uses a renderer and context variables
 * to create the response content.
 *
 *
 * @author Ross Riley <riley.ross@gmail.com>
 */
class BoltResponse extends Response
{
    protected $renderer;
    protected $context = array();
    protected $compiled = false;

    /**
     * Constructor.
     *
     * @param Renderer $renderer An object that is able to render a template with context
     * @param array    $context  An array of context variables
     * @param int      $status   The response status code
     * @param array    $headers  An array of response headers
     */
    public function __construct($renderer, $context = array(), $status = 200, $headers = array())
    {
        parent::__construct(null, $status, $headers);
        $this->renderer = $renderer;
        $this->context = $context;
    }

    /**
     * Factory method for chainability
     *
     * @param Renderer $renderer An object that is able to render a template with context
     * @param array    $context  An array of context variables
     * @param int      $status   The response status code
     * @param array    $headers  An array of response headers
     *
     * @return BoltResponse
     */
    public static function create($renderer = null, $context = array(), $status = 200, $headers = array())
    {
        return new static($renderer, $context, $status, $headers);
    }

    /**
     * Sets the Renderer used to create this Response.
     *
     * @param Renderer $renderer A renderer object
     */
    public function setRenderer($renderer)
    {
        $this->renderer = $renderer;
    }
    
    /**
     * Sets the context variables for this Response.
     *
     * @param array $context
     */
    public function setContext($context)
    {
        $this->context = $context;
    }
    
    /**
     * Returns the renderer.
     */
    public function getRenderer()
    {
        return $this->renderer;
    }
    
    /**
     * Returns the context.
     */
    public function getContext()
    {
        return $this->context;
    }
    
    /**
     * Gets globals from the renderer.
     */
    public function getGlobalContext()
    {
        return $this->renderer->getEnvironment()->getGlobals();
    }
    
    /**
     * Gets the name of the main loaded template.
     */
    public function getTemplate()
    {
        return $this->renderer->getTemplateName();
    }
    
    /**
     * Returns the Response as a string.
     *
     * @return string The Response as an HTTP string
     */
    public function __toString()
    {
        return $this->getContent();
    }
    
    /**
     * Gets content for the current web response.
     *
     * @return Response
     */
    public function getContent()
    {
        if (!$this->compiled) {
            $this->compile();
        }

        return parent::getContent();
    }
    
    /**
     * Compiles the template using the context.
     *
     * @return void
     */
    public function compile()
    {
        $output = $this->getRenderer()->render($this->getContext());
        $this->setContent($output);
        $this->compiled = true;
    }
}