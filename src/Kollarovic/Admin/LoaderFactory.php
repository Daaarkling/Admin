<?php
declare(strict_types=1);

namespace Kollarovic\Admin;

use Nette\Http\IRequest;
use Nette\SmartObject;
use WebLoader\Compiler;
use WebLoader\FileCollection;
use WebLoader\Nette\CssLoader;
use WebLoader\Nette\JavaScriptLoader;



class LoaderFactory implements ILoaderFactory
{
	use SmartObject;

	/** @var string **/
	private $wwwDir;

	/** @var IRequest **/
	private $httpRequest;

	/** @var array */
	private $cssFiles = [];

	/** @var string **/
	private $outputDir = 'webtemp';

	/** @var string **/
	private $root;

	/** @var array */
	private $files = [];

	function __construct(string $wwwDir, IRequest $httpRequest)
	{
		$this->wwwDir = $wwwDir;
		$this->httpRequest = $httpRequest;
		$this->root = __DIR__ . '/assets';
	}


	public function addFile(string $file): LoaderFactory
	{
		$this->files[$file] = $file;
		return $this;
	}


	public function removeFile(string $file): LoaderFactory
	{
		unset($this->cssFiles[$file]);
		return $this;
	}


	public function createCssLoader(): CssLoader
	{
		$fileCollection = $this->createFileCollection(array_filter($this->files, [$this, 'isCss']));
		$compiler = Compiler::createCssCompiler($fileCollection, $this->wwwDir . '/' . $this->outputDir);
		$compiler->addFilter(new \Joseki\Webloader\CssMinFilter());
		$compiler->addFileFilter(new \WebLoader\Nette\CssUrlFilter($this->wwwDir . '/', $this->httpRequest));
		return new CssLoader($compiler, $this->httpRequest->getUrl()->getBasePath() . $this->outputDir);
	}


	public function createJavaScriptLoader(): JavaScriptLoader
	{
		$fileCollection = $this->createFileCollection(array_filter($this->files, [$this, 'isJs']));
		$compiler = Compiler::createJsCompiler($fileCollection, $this->wwwDir . '/' . $this->outputDir);
		$compiler->addFilter(new \Joseki\Webloader\JsMinFilter());
		return new JavaScriptLoader($compiler, $this->httpRequest->getUrl()->getBasePath() . $this->outputDir);
	}


	private function createFileCollection(array $files): FileCollection
	{
		$fileCollection = new FileCollection($this->root);
		foreach($files as $file) {
			if ($this->isRemoteFile($file)) {
				$fileCollection->addRemoteFile($file);
			} else {
				$fileCollection->addFile($file);
			}
		}
		return $fileCollection;
	}


	private function isRemoteFile($file): bool
	{
		return (filter_var($file, FILTER_VALIDATE_URL) or strpos($file, '//') === 0);
	}


	private function isCss($file)
	{
		return preg_match('~\.css$~', $file);
	}


	private function isJs($file)
	{
		return preg_match('~(\.js$|\/js)~', $file);
	}

	/**
	 * @return string
	 */
	public function getWwwDir(): string
	{
		return $this->wwwDir;
	}

	/**
	 * @param string $wwwDir
	 */
	public function setWwwDir(string $wwwDir)
	{
		$this->wwwDir = $wwwDir;
	}

	/**
	 * @return IRequest
	 */
	public function getHttpRequest(): IRequest
	{
		return $this->httpRequest;
	}

	/**
	 * @param IRequest $httpRequest
	 */
	public function setHttpRequest(IRequest $httpRequest)
	{
		$this->httpRequest = $httpRequest;
	}

	/**
	 * @return array
	 */
	public function getCssFiles(): array
	{
		return $this->cssFiles;
	}

	/**
	 * @param array $cssFiles
	 */
	public function setCssFiles(array $cssFiles)
	{
		$this->cssFiles = $cssFiles;
	}

	/**
	 * @return string
	 */
	public function getOutputDir(): string
	{
		return $this->outputDir;
	}

	/**
	 * @param string $outputDir
	 */
	public function setOutputDir(string $outputDir)
	{
		$this->outputDir = $outputDir;
	}

	/**
	 * @return string
	 */
	public function getRoot(): string
	{
		return $this->root;
	}

	/**
	 * @param string $root
	 */
	public function setRoot(string $root)
	{
		$this->root = $root;
	}

	/**
	 * @return array
	 */
	public function getFiles(): array
	{
		return $this->files;
	}

	/**
	 * @param array $files
	 */
	public function setFiles(array $files)
	{
		$this->files = $files;
	}
}