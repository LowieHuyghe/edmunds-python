<?php

/**
 * Edmunds
 *
 * The fast PHP framework for building web applications.
 *
 * @license   This file is subject to the terms and conditions defined in file 'license.md', which is part of this source code package.
 */

namespace Edmunds\Mail\Transports;

use Swift_Attachment;
use Swift_Events_EventListener;
use Swift_Mime_Message;
use Swift_Transport;
use google\appengine\api\mail\Message as GAEMessage;

/**
 * The Google App Engine Mail Api Transport driver
 */
class GAEMailApiTransport implements Swift_Transport
{
	/**
	 * Create a new GAEMailApiTransport instance.
	 *
	 * @return GAEMailApiTransport
	 */
	public static function newInstance()
	{
		return new self();
	}

	/**
	 * The headers allowed by GAE
	 * @var array
	 */
	protected $gaeAllowedHeaders = array(
		'auto-submitted', 'in-reply-to', 'list-id', 'list-unsubscribe',
		'on-behalf-of', 'references', 'resent-date', 'resent-from', 'resent-to'
	);

	/**
	 * Has mailing started
	 * @var bool
	 */
	protected $started = false;


	/**
	 * Send the given Message.
	 *
	 * @param Swift_Mime_Message $message
	 * @param string[]           $failedRecipients An array of failures by-reference
	 *
	 * @return int
	 */
	public function send(Swift_Mime_Message $message, &$failedRecipients = null)
	{
		$gaeMessage = new GAEMessage();

		if ($sender = $this->getSender($message)) $gaeMessage->setSender($sender);
		if ($replyTo = $this->getReplyTo($message)) $gaeMessage->setReplyTo($replyTo);
		if ($to = $this->getTo($message)) $gaeMessage->addTo($to);
		if ($cc = $this->getCc($message)) $gaeMessage->addCc($cc);
		if ($bcc = $this->getBcc($message)) $gaeMessage->addBcc($bcc);
		if ($headers = $this->getHeaders($message)) $gaeMessage->addHeaderArray($headers);
		if ($subject = $this->getSubject($message)) $gaeMessage->setSubject($subject);
		if ($htmlBody = $this->getHtmlBody($message)) $gaeMessage->setHtmlBody($htmlBody);
		if ($textBody = $this->getTextBody($message)) $gaeMessage->setTextBody($textBody);
		if ($attachments = $this->getAttachements($message)) $gaeMessage->addAttachmentsArray($attachments);

		$gaeMessage->send();

		return count((array) $message->getTo())
			+ count((array) $message->getCc())
			+ count((array) $message->getBcc());
	}

	/**
	 * Get the sender address
	 * @param  Swift_Mime_Message $message
	 * @return string
	 */
	protected function getSender(Swift_Mime_Message $message)
	{
		if ($sender = $message->getSender())
		{
			return array_keys($sender)[0];
		}
		elseif ($sender = $message->getFrom())
		{
			return array_keys($sender)[0];
		}
		else
		{
			return null;
		}
	}

	/**
	 * Get the reply to address
	 * @param  Swift_Mime_Message $message
	 * @return string
	 */
	protected function getReplyTo(Swift_Mime_Message $message)
	{
		if ($replyTo = $message->getReplyTo())
		{
			return array_keys($replyTo)[0];
		}
		else
		{
			return null;
		}
	}

	/**
	 * Get the to adresses
	 * @param  Swift_Mime_Message $message
	 * @return array
	 */
	protected function getTo(Swift_Mime_Message $message)
	{
		if ($to = $message->getTo())
		{
			return array_keys($to);
		}
		else
		{
			return null;
		}
	}

	/**
	 * Get the cc addresses
	 * @param  Swift_Mime_Message $message
	 * @return array
	 */
	protected function getCc(Swift_Mime_Message $message)
	{
		if ($cc = $message->getCc())
		{
			return array_keys($cc);
		}
		else
		{
			return null;
		}
	}

	/**
	 * Get the bcc addresses
	 * @param  Swift_Mime_Message $message
	 * @return array
	 */
	protected function getBcc(Swift_Mime_Message $message)
	{
		if ($bcc = $message->getBcc())
		{
			return array_keys($bcc);
		}
		else
		{
			return null;
		}
	}

	/**
	 * Get the headers for the mail
	 * @param  Swift_Mime_Message $message
	 * @return array
	 */
	protected function getHeaders(Swift_Mime_Message $message)
	{
		$headers = array();

		foreach ($message->getHeaders()->getAll() as $swiftHeader)
		{
			$name = $swiftHeader->getFieldName();

			if (in_array($name, $this->gaeAllowedHeaders))
			{
				$value = trim(str_replace($name . ':', '', $swiftHeader->toString()));

				$headers[$name] = $value;
			}
		}

		return $headers;
	}

	/**
	 * Get the subject
	 * @param  Swift_Mime_Message $message
	 * @return string
	 */
	protected function getSubject(Swift_Mime_Message $message)
	{
		return $message->getSubject();
	}

	/**
	 * Get the html body
	 * @param  Swift_Mime_Message $message
	 * @return string
	 */
	protected function getHtmlBody(Swift_Mime_Message $message)
	{
		return $message->getBody();
	}

	/**
	 * Get the text body
	 * @param  Swift_Mime_Message $message
	 * @return string
	 */
	protected function getTextBody(Swift_Mime_Message $message)
	{
		return null;
	}

	/**
	 * Get the attachments
	 * @param  Swift_Mime_Message $message
	 * @return array
	 */
	protected function getAttachements(Swift_Mime_Message $message)
	{
		$attachments = array();

		foreach ($message->getChildren() as $child)
		{
			if ($child instanceof Swift_Attachment)
			{
				$attachments[] = array(
					'name' => $child->getFilename(),
					'data' => $child->getBody(),
					//'content_id' => '',
				);
			}
		}

		return $attachments;
	}

	/**
	 * Set mailing as started
	 * @return void
	 */
	public function start()
	{
		$this->started = true;
	}

	/**
	 * Set mailing as stopped
	 * @return void
	 */
	public function stop()
	{
		$this->started = false;
	}

	/**
	 * Check if mailing has started
	 * @return bool
	 */
	public function isStarted()
	{
		return $this->started;
	}

	public function registerPlugin(Swift_Events_EventListener $plugin) {}
}