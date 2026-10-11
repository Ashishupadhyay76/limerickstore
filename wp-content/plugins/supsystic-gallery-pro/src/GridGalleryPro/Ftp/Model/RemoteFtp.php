<?php

class GridGalleryPro_Ftp_Model_RemoteFtp extends GridGalleryPro_Ftp_Model_AbstractFtp
{
  /**
   * @var resource $_ftp_conn connection to remote ftp server
   */
  private $_ftp_conn;

  /**
   * @var string $_tmpFolder temp folder to save images before import them to gallery
   */
  private $_tmpFolder;

  /**
   * @var string|null $host  ftp host
   */
  public $host;

  /**
   * @var int|null $port  ftp port
   */
  public $port;

  /**
   * @var int $defaultPort default ftp port
   */
  private $defaultPort = 21;

  /**
   * @var string|null $ftpUsername  ftp username
   */
  public $ftpUsername;

  /**
   * @var string|null $ftpPassword  ftp password
   */
  public $ftpPassword;

  /**
   * @inheritdoc
   */
  function __construct(RscSgg_Common_Collection $config)
  {
    parent::__construct($config);

    $this->host = $config->get('host');
    $this->port = $config->get('port');
    $this->ftpUsername = $config->get('ftpUsername');
    $this->ftpPassword = $config->get('ftpPassword');

    $wpUploadDir = wp_upload_dir();
    $this->_tmpFolder = $wpUploadDir['basedir'] . '/grid-gallery/ftp-tmp/';
  }

  /**
   * @inheritdoc
   */
  public function parseImages()
  {
    if (!is_dir($this->_tmpFolder) && !@mkdir($this->_tmpFolder)) {
      $this->setParseError('Can not create temp folder');
      return false;
    }

    $files = glob($this->_tmpFolder . '*');
    foreach ($files as $file) {
      @unlink($file);
    }

    try {
      $this->connectFtp();
    } catch (GridGalleryPro_Ftp_Exception_FtpConnectionException $e) {
      $this->setParseError($e->getMessage());
      return false;
    }

    $files = ftp_nlist($this->_ftp_conn, '.');
    $imagesUrl = [];

    $folder = $this->getImgFolderUrl($this->_tmpFolder);

    foreach ($files as $file) {
      $localFile = str_replace('%', '', urlencode($file));
      if ($this->isImageFile($file) && @ftp_get($this->_ftp_conn, $this->_tmpFolder . $localFile, $file, FTP_BINARY)) {
        $imagesUrl[] = $folder . $localFile;
      }
    }

    if (empty($imagesUrl)) {
      $this->setParseError('Can not find images in provided folder');
      return false;
    } else {
      $this->images = $imagesUrl;
      return true;
    }
  }

  /**
   * Connect to remote ftp server
   * @throws GridGalleryPro_Ftp_Exception_FtpConnectionException if connection is failed
   */
  private function connectFtp()
  {
    if (!($this->_ftp_conn = @ftp_connect($this->host, empty($this->port) ? $this->defaultPort : $this->port))) {
      throw new GridGalleryPro_Ftp_Exception_FtpConnectionException('Can not connect to remote FTP server');
    }

    if (!@ftp_login($this->_ftp_conn, $this->ftpUsername, $this->ftpPassword)) {
      throw new GridGalleryPro_Ftp_Exception_FtpConnectionException('Wrong username or password');
    }

    @ftp_pasv($this->_ftp_conn, true);

    if (!@ftp_chdir($this->_ftp_conn, $this->folderName)) {
      throw new GridGalleryPro_Ftp_Exception_FtpConnectionException('Can not open directory');
    }
  }
}
