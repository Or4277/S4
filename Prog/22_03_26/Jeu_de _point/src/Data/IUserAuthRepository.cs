using System.Threading;
using System.Threading.Tasks;

namespace Data;

public interface IUserAuthRepository
{
    Task<bool> ValidateCredentialsAsync(
        string nom,
        string motDePasse,
        CancellationToken cancellationToken = default);
}
