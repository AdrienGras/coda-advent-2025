# Gift Machine - SOLID Principles Implementation

This project demonstrates a perfect implementation of SOLID principles in a gift creation and delivery system. Each principle is carefully applied to ensure maintainable, extensible, and testable code.

## 🎯 SOLID Principles Overview

### 1. **S**ingle Responsibility Principle (SRP)

**"A class should have only one reason to change"**

Each class in this project has a single, well-defined responsibility:

- **`GiftMachine`**: Orchestrates the gift creation workflow
- **`Logger`**: Handles logging operations exclusively
- **`ErrorHandler`**: Manages error handling and reporting
- **`Wrapper`**: Responsible only for wrapping gifts
- **`Ribbon`**: Handles ribbon addition to gifts
- **`Delivery`**: Manages gift delivery logistics
- **`BuilderRegistry`**: Manages the registration and retrieval of gift builders
- **`BookBuilder`, `TeddyBuilder`, `CarBuilder`, `DollBuilder`**: Each builds one specific type of gift

**Example**: The `Logger` class only logs messages. It doesn't handle errors, wrap gifts, or manage deliveries. If logging requirements change, only this class needs modification.

```php
class Logger implements LoggerInterface
{
    public function log(string $message): void
    {
        $time = date('H:i:s');
        echo "[$time] $message\n";
    }
}
```

### 2. **O**pen/Closed Principle (OCP)

**"Classes should be open for extension but closed for modification"**

The project is designed to add new functionality without modifying existing code:

#### Adding New Gift Types
To add a new gift type (e.g., `RobotBuilder`), you simply:
1. Create a new builder implementing `GiftBuilderInterface`
2. Register it in the `BuilderRegistry`
3. **No modification of existing builders or `GiftMachine` required**

```php
class RobotBuilder implements GiftBuilderInterface
{
    public function build(string $recipient): string
    {
        return "🤖 Robot for $recipient";
    }
}
```

#### Extending Functionality
- Want a different logging mechanism? Create a new class implementing `LoggerInterface`
- Need a different delivery method? Create a new class implementing `GiftDeliveryInterface`
- The core `GiftMachine` remains unchanged

### 3. **L**iskov Substitution Principle (LSP)

**"Derived classes must be substitutable for their base classes"**

All implementations can be substituted with their interfaces without breaking the system:

- Any class implementing `GiftBuilderInterface` can be used by `BuilderRegistry`
- Any `LoggerInterface` implementation can be injected into `GiftMachine` or `ErrorHandler`
- Any `GiftWrapperInterface`, `GiftRibbonInterface`, or `GiftDeliveryInterface` implementation works seamlessly

**Example**: The `GiftMachine` depends on `LoggerInterface`, not on a concrete `Logger` class. You could substitute it with a `FileLogger`, `DatabaseLogger`, or `SilentLogger` without any issues:

```php
class GiftMachine
{
    public function __construct(
        private GiftBuilderRegistryInterface $builderRegistry,
        private GiftWrapperInterface $wrapper,
        private GiftRibbonInterface $ribbon,
        private GiftDeliveryInterface $delivery,
        private LoggerInterface $logger,  // ← Any LoggerInterface implementation works
        private ErrorHandlerInterface $errorHandler
    ) {}
}
```

### 4. **I**nterface Segregation Principle (ISP)

**"Clients should not be forced to depend on interfaces they don't use"**

The project uses small, focused interfaces rather than large, monolithic ones:

- **`LoggerInterface`**: Only has `log(string $message): void`
- **`ErrorHandlerInterface`**: Only has `handle(string $message): void`
- **`GiftWrapperInterface`**: Only has `wrap(string $gift): void`
- **`GiftRibbonInterface`**: Only has `addRibbon(string $gift): void`
- **`GiftDeliveryInterface`**: Only has `deliver(string $gift, string $recipient): void`
- **`GiftBuilderInterface`**: Only has `build(string $recipient): string`

Each interface defines only the methods relevant to its specific responsibility. Classes implementing these interfaces aren't forced to implement methods they don't need.

**Counter-example** (what we avoid):
```php
// BAD: Fat interface forcing unnecessary implementations
interface GiftServiceInterface
{
    public function log(string $message): void;
    public function handleError(string $error): void;
    public function wrap(string $gift): void;
    public function addRibbon(string $gift): void;
    public function deliver(string $gift, string $recipient): void;
}
```

### 5. **D**ependency Inversion Principle (DIP)

**"Depend on abstractions, not concretions"**

High-level modules (`GiftMachine`) don't depend on low-level modules (concrete implementations). Both depend on abstractions (interfaces):

#### Dependency Flow

```
GiftMachine (high-level)
    ↓ depends on
Interfaces (abstractions)
    ↑ implemented by
Concrete Classes (low-level)
```

#### Constructor Injection
The `GiftMachine` receives all its dependencies through constructor injection, depending only on interfaces:

```php
public function __construct(
    private GiftBuilderRegistryInterface $builderRegistry,  // ← Interface
    private GiftWrapperInterface $wrapper,                  // ← Interface
    private GiftRibbonInterface $ribbon,                    // ← Interface
    private GiftDeliveryInterface $delivery,                // ← Interface
    private LoggerInterface $logger,                        // ← Interface
    private ErrorHandlerInterface $errorHandler             // ← Interface
) {}
```

**Benefits**:
- Easy to test: inject mock implementations in tests
- Easy to swap implementations: change behavior without modifying `GiftMachine`
- Loose coupling: changes in concrete classes don't affect `GiftMachine`

#### Example: ErrorHandler
Even `ErrorHandler` follows DIP by depending on `LoggerInterface`:

```php
class ErrorHandler implements ErrorHandlerInterface
{
    public function __construct(LoggerInterface $logger)  // ← Depends on interface
    {
        $this->logger = $logger;
    }
}
```

## 🎁 Architecture Benefits

By following SOLID principles, this codebase achieves:

1. **Maintainability**: Changes are localized and predictable
2. **Testability**: Easy to mock dependencies and test in isolation
3. **Extensibility**: New features require minimal changes
4. **Flexibility**: Swap implementations without breaking existing code
5. **Clarity**: Each component has a clear, single purpose

## 🏗️ Project Structure

```
src/Gift/
├── GiftMachine.php              # Orchestrator (uses all interfaces)
├── Logger.php                   # Logging implementation
├── ErrorHandler.php             # Error handling implementation
├── Wrapper.php                  # Gift wrapping implementation
├── Ribbon.php                   # Ribbon addition implementation
├── Delivery.php                 # Delivery implementation
├── Impl/                        # Core interfaces
│   ├── LoggerInterface.php
│   ├── ErrorHandlerInterface.php
│   ├── GiftWrapperInterface.php
│   ├── GiftRibbonInterface.php
│   └── GiftDeliveryInterface.php
└── Builder/                     # Gift builder pattern
    ├── BuilderRegistry.php      # Builder registry implementation
    ├── BookBuilder.php          # Book gift builder
    ├── TeddyBuilder.php         # Teddy bear gift builder
    ├── CarBuilder.php           # Car gift builder
    ├── DollBuilder.php          # Doll gift builder
    └── Impl/                    # Builder interfaces
        ├── GiftBuilderInterface.php
        └── GiftBuilderRegistryInterface.php
```

## 🧪 Testing & Extensibility

Thanks to SOLID principles, this system is highly testable:

```php
// Mock implementations for testing
$mockLogger = new class implements LoggerInterface {
    public function log(string $message): void {
        // Test implementation
    }
};

$mockDelivery = new class implements GiftDeliveryInterface {
    public function deliver(string $gift, string $recipient): void {
        // Test implementation
    }
};

// GiftMachine works with any implementation
$giftMachine = new GiftMachine(
    $builderRegistry,
    $wrapper,
    $ribbon,
    $mockDelivery,  // ← Mock for testing
    $mockLogger,    // ← Mock for testing
    $errorHandler
);
```

## 📝 Conclusion

This project serves as a practical demonstration that following SOLID principles leads to professional, production-ready code. Every design decision was made with these principles in mind, resulting in a system that is both robust and flexible.

The gift creation workflow is just an example - these same principles apply to any software system, from small utilities to large enterprise applications.

**Remember**: SOLID principles are not rules to be followed blindly, but guidelines that help us write better, more maintainable code. This project shows how they work together to create a cohesive, well-designed system.
