<?php // phpcs:ignoreFile ?>
<?php 

/** @psalm-suppress UndefinedClass */ /** @psalm-suppress UndefinedClass */ abstract class Base implements Observable
{
	/** @psalm-suppress MissingPropertyType */ /** @psalm-suppress MissingPropertyType */ protected $registry = false;
	/** @psalm-suppress MissingPropertyType */ /** @psalm-suppress MissingPropertyType */ protected $db = false;
	/** @psalm-suppress MissingPropertyType */ /** @psalm-suppress MissingPropertyType */ protected $modules = false;
	/** @psalm-suppress MissingPropertyType */ /** @psalm-suppress MissingPropertyType */ protected $session = false;
	/** @psalm-suppress MissingPropertyType */ /** @psalm-suppress MissingPropertyType */ protected $common = false;
	/** @psalm-suppress MissingPropertyType */ /** @psalm-suppress MissingPropertyType */ protected $status = null;		
	
	
	public function __construct()
	{		
		/* @phpstan-ignore-line */ $this->db = DatabaseManager::getInstance( array( __DB_HOST , __DB_USER , __DB_PASS , __DB_NAME ) );			
		/* @phpstan-ignore-line */ $this->registry = Registry::getInstance();		
		$this->registry->__DB = $this->db;	
		
        /* @phpstan-ignore-line */ $this->session = SessionManager::getInstance();
        $this->registry->__SESSION = $this->session;
        
        /* @phpstan-ignore-line */ $this->modules = Modules::getInstance( array( $this ) );
        $this->registry->__MODULES = $this->modules;
        
        /* @phpstan-ignore-line */ $this->common = ViewCommon::getInstance();
        $this->registry->__COMMON = $this->common;        
	}	
	
	
    /* @phpstan-ignore-line */ /** @psalm-suppress MissingOverrideAttribute, MissingReturnType, UndefinedClass */ /** @psalm-suppress MissingOverrideAttribute, MissingReturnType, UndefinedClass */ #[\Override] public function attach( Observer $observer )
    {
        if( ! isset( $this->registry->__OBSERVERS[ get_class( $observer ) ] ) )
        {
            $this->registry->__OBSERVERS[ get_class( $observer ) ] = $observer;   
        }
    }

    
    /* @phpstan-ignore-line */ /** @psalm-suppress MissingOverrideAttribute, MissingReturnType, UndefinedClass */ /** @psalm-suppress MissingOverrideAttribute, MissingReturnType, UndefinedClass */ #[\Override] public function detatch( Observer $observer ) // codespell:ignore detatch
    {
        unset( $this->registry->__OBSERVERS[ get_class($observer) ] );
    }


    /** @psalm-suppress MissingOverrideAttribute, MissingReturnType */ /** @psalm-suppress MissingOverrideAttribute, MissingReturnType */ #[\Override] public function notify()
    {
        /** @psalm-suppress UnusedVariable */ /** @psalm-suppress UnusedVariable */ foreach( $this->registry->__OBSERVERS as $key => $observer )
        {
            $observer->update( $this , $this->status );
        }
    }

    
    /** @psalm-suppress MissingOverrideAttribute, MissingReturnType */ /** @psalm-suppress MissingOverrideAttribute, MissingReturnType */ #[\Override] public function status()
    {
        return $this->status;
    }
}